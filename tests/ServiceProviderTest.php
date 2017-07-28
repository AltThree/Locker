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

namespace AltThree\Tests\Locker;

use AltThree\Locker\Connections\ConnectionInterface;
use AltThree\Locker\Connections\IlluminateConnection;
use AltThree\Locker\Connections\PredisConnection;
use AltThree\Locker\Http\Middleware\LockingMiddleware;
use AltThree\Locker\Locker;
use GrahamCampbell\TestBenchCore\ServiceProviderTrait;
use Illuminate\Redis\Connections\Connection;

/**
 * This is the service provider test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class ServiceProviderTest extends AbstractTestCase
{
    use ServiceProviderTrait;

    public function testConnectionIsInjectable()
    {
        $this->assertIsInjectable(ConnectionInterface::class);

        $this->assertInstanceOf(
            class_exists(Connection::class) ? IlluminateConnection::class : PredisConnection::class,
            $this->app->make('locker.connection')
        );
    }

    public function testLockerIsInjectable()
    {
        $this->assertIsInjectable(Locker::class);
    }

    public function testMiddlewareIsInjectable()
    {
        $this->assertIsInjectable(LockingMiddleware::class);
    }
}
