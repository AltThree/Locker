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

use AltThree\Locker\Exceptions\UnableToAcquireLockException;
use Closure;
use Predis\ClientInterface;

/**
 * This is the locker class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 * @author James Brooks <james@alt-three.com>
 */
class Locker
{
    /**
     * The redis client instance.
     *
     * @var \Predis\ClientInterface
     */
    protected $redis;

    /**
     * Create a new locker instance.
     *
     * @param \Predis\ClientInterface $redis
     *
     * @return void
     */
    public function __construct(ClientInterface $redis)
    {
        $this->redis = $redis;
    }

    /**
     * Make a new lock instance.
     *
     * Note that we will not attempt to acquire the lock at this point.
     *
     * @param string $name
     * @param int    $timeout
     * @param int    $play
     * @param int    $interval
     * @param int    $attempts
     *
     * @return \AltThree\Locker\Lock
     */
    public function make($name, $timeout, $play = 500, $interval = 100, $attempts = 128)
    {
        return new Lock($this->redis, $name, $timeout, $play, $interval, $attempts);
    }

    /**
     * Acquire the a lock for, and then execute the function.
     *
     * If we were unable to acquire the lock, after the specified number of
     * attempts, then we'll throw an exception.
     *
     * @param \Closure $function
     * @param string   $name
     * @param int      $timeout
     * @param int      $play
     * @param int      $interval
     * @param int      $attempts
     *
     * @throws \AltThree\Locker\Exceptions\UnableToAcquireLockException
     *
     * @return mixed
     */
    public function execute(Closure $function, $name, $timeout, $play = 500, $interval = 100, $attempts = 128)
    {
        $lock = $this->make($name, $timeout, $play, $interval, $attempts);

        if (!$lock->acquire()) {
            throw new UnableToAcquireLockException("Unable to acquire lock on {$name}.");
        }

        try {
            $result = $function();
        } finally {
            $lock->release();
        }

        return $result;
    }
}
