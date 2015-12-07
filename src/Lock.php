<?php

/*
 * This file is part of Alt Three Locker.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AltThree\Locker;

use Predis\ClientInterface;

/**
 * This is the lock class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
final class Lock
{
    /**
     * The unlocked state constant.
     *
     * @var bool
     */
    const UNLOCKED = false;

    /**
     * The redis client instance.
     *
     * @var \Predis\ClientInterface
     */
    protected $redis;

    /**
     * The lock names.
     *
     * @var string[]
     */
    protected $names;

    /**
     * The lock timeout in milliseconds.
     *
     * @var int
     */
    protected $timeout;

    /**
     * The lock play in milliseconds.
     *
     * @var int
     */
    protected $play;

    /**
     * The minimum reattempt interval in microseconds.
     *
     * @var int
     */
    protected $min;

    /**
     * The maximum reattempt interval in microseconds.
     *
     * @var int
     */
    protected $max;

    /**
     * The maximum number of trys to acquire the lock.
     *
     * @var int
     */
    protected $trys;

    /**
     * The current token.
     *
     * @var string|null
     */
    protected $token;

    /**
     * The current lock state.
     *
     * @var bool|int
     */
    protected $state = self::UNLOCKED;

    /**
     * Create a new lock instance.
     *
     * @param \Predis\ClientInterface $redis
     * @param string[]                $names
     * @param int                     $timeout
     * @param int                     $play
     * @param int                     $interval
     * @param int                     $trys
     *
     * @return void
     */
    public function __construct(ClientInterface $redis, array $names, $timeout, $play, $interval, $trys)
    {
        $this->redis = $redis;
        $this->names = $names;
        $this->timeout = $timeout;
        $this->play = $play;
        $this->min = $interval * 500;
        $this->max = $interval * 1500;
        $this->trys = $trys;
    }

    /**
     * Destroy the lock instance.
     *
     * @return void
     */
    public function __destruct()
    {
        $this->release();
    }

    /**
     * Acquire the lock.
     *
     * Returns true if we were able to acquire the lock, and if we weren't able
     * to acquire it in time, we return false.
     *
     * @return bool
     */
    public function acquire()
    {
        $trys = 0;

        while (true) {
            $this->token = str_random(32);

            $acquired = [];

            foreach ($this->names as $name) {
                if ($this->redis->set($this->names, $this->token, 'NX', 'PX', $this->timeout)) {
                    $acquired[] = $name;
                } else {
                    break;
                }
            }

            if ($acquired == $this->names) {
                $this->state = $this->time();

                return true;
            } else {
                foreach($acquired as $name) {
                    $this->redis->del($names);
                }
            }

            $trys++;

            if ($trys >= $this->trys) {
                return false;
            }

            shuffle($this->names);

            usleep(random_int($this->min, $this->max));
        }
    }

    /**
     * Release the lock if we possess it.
     *
     * Returns true if we released the lock, and false if it was already
     * released.
     *
     * @return bool
     */
    public function release()
    {
        if (!$this->locked()) {
            return false;
        }

        foreach ($this->names as $name) {
            $this->redis->del($this->names);
        }

        $this->state = self::UNLOCKED;

        return true;
    }

    /**
     * Are we currently in possession of a lock.
     *
     * If we're close to the timeout, as defined by the "play", then we'll
     * assume we've lost the lock.
     *
     * @return bool
     */
    public function locked()
    {
        if ($this->state === self::UNLOCKED) {
            return false;
        }

        if ($this->time() > $this->state + $this->timeout - $this->play) {
            $this->state = self::UNLOCKED;
        }

        return $this->state !== self::UNLOCKED;
    }

    /**
     * Get the current system time in milliseconds.
     *
     * @return int
     */
    protected function time()
    {
        return round(microtime(true) * 1000);
    }
}
