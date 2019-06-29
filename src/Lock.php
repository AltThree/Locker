<?php

declare(strict_types=1);

/*
 * This file is part of Alt Three Locker.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AltThree\Locker;

use AltThree\Locker\Connections\ConnectionInterface;
use Illuminate\Support\Str;

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
     * The connection instance.
     *
     * @var \AltThree\Locker\Connections\ConnectionInterface
     */
    protected $connection;

    /**
     * The lock name.
     *
     * @var string
     */
    protected $name;

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
     * The minimum retry interval in microseconds.
     *
     * @var int
     */
    protected $min;

    /**
     * The maximum retry interval in microseconds.
     *
     * @var int
     */
    protected $max;

    /**
     * The maximum number of attempts to acquire the lock.
     *
     * @var int
     */
    protected $attempts;

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
     * @param \AltThree\Locker\Connections\ConnectionInterface $connection
     * @param string                                           $name
     * @param int                                              $timeout
     * @param int                                              $play
     * @param int                                              $interval
     * @param int                                              $attempts
     *
     * @return void
     */
    public function __construct(ConnectionInterface $connection, string $name, int $timeout, int $play, int $interval, int $attempts)
    {
        $this->connection = $connection;
        $this->name = $name;
        $this->timeout = $timeout;
        $this->play = $play;
        $this->min = $interval * 500;
        $this->max = $interval * 1500;
        $this->attempts = $attempts;
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
        $attempts = 0;

        while (true) {
            $this->token = Str::random(32);

            if ($this->connection->store($this->name, $this->token, $this->timeout)) {
                $this->state = $this->time();

                return true;
            }

            $attempts++;

            if ($attempts >= $this->attempts) {
                return false;
            }

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

        $this->connection->remove($this->name);

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
        return (int) round(microtime(true) * 1000);
    }
}
