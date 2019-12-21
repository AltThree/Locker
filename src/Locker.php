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
use AltThree\Locker\Exceptions\UnableToAcquireLockException;
use Closure;

/**
 * This is the locker class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 * @author James Brooks <james@alt-three.com>
 */
class Locker
{
    /**
     * The connection instance.
     *
     * @var \AltThree\Locker\Connections\ConnectionInterface|callable
     */
    protected $connection;

    /**
     * Create a new locker instance.
     *
     * @param \AltThree\Locker\Connections\ConnectionInterface|callable $connection
     *
     * @return void
     */
    public function __construct($connection)
    {
        $this->connection = $connection;
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
    public function make(string $name, int $timeout, int $play = 500, int $interval = 100, int $attempts = 128)
    {
        return new Lock($this->resolveConnection(), $name, $timeout, $play, $interval, $attempts);
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
    public function execute(Closure $function, string $name, int $timeout, int $play = 500, int $interval = 100, int $attempts = 128)
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

    /**
     * Resolve the connection instance.
     *
     * @return \AltThree\Locker\Connections\ConnectionInterface
     */
    protected function resolveConnection()
    {
        if ($this->connection instanceof ConnectionInterface) {
            return $this->connection;
        }

        $c = $this->connection;

        return $this->connection = $c();
    }
}
