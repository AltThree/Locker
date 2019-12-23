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
use AltThree\Locker\Connections\LockProviderConnection;
use AltThree\Locker\Http\Middleware\LockingMiddleware;
use AltThree\Locker\Locker;
use GrahamCampbell\TestBenchCore\ServiceProviderTrait;

/**
 * This is the service provider test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class ServiceProviderTest extends AbstractTestCase
{
    use ServiceProviderTrait;

    /**
     * Setup the application environment.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app->config->set('database.redis.client', 'predis');
    }

    public function testConnectionIsInjectable()
    {
        $this->assertIsInjectable(ConnectionInterface::class);

        $this->assertInstanceOf(
            version_compare($this->app->version(), '5.8') >= 0 ? LockProviderConnection::class : IlluminateConnection::class,
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
