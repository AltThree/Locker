<?php

/*
 * This file is part of Alt Three Locker.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AltThree\Locker\Exceptions;

use Exception;

/**
 * This is the unable to acquire lock exception class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class UnableToAcquireLockException extends Exception implements LockerExceptionInterface
{
    //
}
