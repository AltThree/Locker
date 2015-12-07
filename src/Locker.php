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
     * Acquire the a lock for, and then excecute the function.
     *
     * If we were unable to acquire the lock, after the specified number of
     * retrys, then we'll throw an exception.
     *
     * @param \Closure $function
     * @param string   $name
     * @param int      $timeout
     * @param int      $play
     * @param int      $interval
     * @param int      $trys
     *
     * @throws \AltThree\Locker\Exceptions\UnableToAcquireLockException
     *
     * @return mixed
     */
    public function execute(Closure $function, $name, $timeout, $play = 500, $interval = 100, $trys = 128)
    {
        $lock = new Lock($this->redis, $name, $timeout, $play, $interval, $trys);

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
