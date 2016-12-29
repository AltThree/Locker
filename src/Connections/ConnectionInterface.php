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

/**
 * This is the connection interface.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
interface ConnectionInterface
{
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
    public function store($key, $value, $timeout);

    /**
     * Remove the value at the given key.
     *
     * @param string $key
     *
     * @return void
     */
    public function remove($key);
}
