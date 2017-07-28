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

use Predis\ClientInterface;

/**
 * This is the predis connection class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class PredisConnection implements ConnectionInterface
{
    /**
     * The predis client instance.
     *
     * @var \Predis\ClientInterface
     */
    protected $predis;

    /**
     * Create a new predis connection class.
     *
     * @param \Predis\ClientInterface $predis
     *
     * @return void
     */
    public function __construct(ClientInterface $predis)
    {
        $this->predis = $predis;
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
        return $this->predis->set($key, $value, 'NX', 'PX', $timeout);
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
        $this->predis->del($key);
    }
}
