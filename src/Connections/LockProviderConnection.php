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

namespace AltThree\Locker\Connections;

use Illuminate\Contracts\Cache\LockProvider;

/**
 * This is the lock provider connection class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class LockProviderConnection implements ConnectionInterface
{
    /**
     * The lock provider instance.
     *
     * @var \Illuminate\Contracts\Cache\LockProvider
     */
    protected $provider;

    /**
     * Create a new lock provider connection class.
     *
     * @param \Illuminate\Contracts\Cache\LockProvider $provider
     *
     * @return void
     */
    public function __construct(LockProvider $provider)
    {
        $this->provider = $provider;
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
        return $this->provider->lock($key, intdiv($timeout, 1000), $value)->get();
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
        return $this->provider->lock($key)->forceRelease();
    }
}
