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

namespace AltThree\Tests\Locker\Facades;

use AltThree\Locker\Facades\Locker as LockerFacade;
use AltThree\Locker\Locker;
use AltThree\Tests\Locker\AbstractTestCase;
use GrahamCampbell\TestBenchCore\FacadeTrait;

/**
 * This is the locker facade test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class LockerTest extends AbstractTestCase
{
    use FacadeTrait;

    /**
     * Get the facade accessor.
     *
     * @return string
     */
    protected function getFacadeAccessor()
    {
        return 'locker';
    }

    /**
     * Get the facade class.
     *
     * @return string
     */
    protected function getFacadeClass()
    {
        return LockerFacade::class;
    }

    /**
     * Get the facade root.
     *
     * @return string
     */
    protected function getFacadeRoot()
    {
        return Locker::class;
    }
}
