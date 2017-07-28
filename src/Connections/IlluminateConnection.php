<?php

/*
 * This file is part of Alt Three Locker.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AltThree\Locker\Connections;

use Illuminate\Redis\Connections\Connection;

/**
 * This is the illuminate connection class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class IlluminateConnection implements ConnectionInterface
{
    /**
     * The illuminate connection instance.
     *
     * @var \Illuminate\Redis\Connections\Connection
     */
    protected $illuminate;

    /**
     * Create a new illuminate connection class.
     *
     * @param \Illuminate\Redis\Connections\Connection $illuminate
     *
     * @return void
     */
    public function __construct(Connection $illuminate)
    {
        $this->illuminate = $illuminate;
    }

    /**
     * Store the value at the given key.
     *
     * Returns true if the key was fresh.
     *
     * @param string $key
     * @param string $value
     * @param int    $timeout
     *
     * @return bool
     */
    public function store(string $key, string $value, int $timeout)
    {
        return $this->illuminate->set($key, $value, 'NX', 'PX', $timeout);
    }

    /**
     * Remove the value at the given key.
     *
     * @param string $key
     *
     * @return void
     */
    public function remove(string $key)
    {
        $this->illuminate->del($key);
    }
}
