<?php

/*
 * This file is part of Alt Three Locker.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AltThree\Tests\Locker;

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

    public function testLockerIsInjectable()
    {
        $this->assertIsInjectable(Locker::class);
    }

    public function testMiddlewareIsInjectable()
    {
        $this->assertIsInjectable(LockingMiddleware::class);
    }
}
