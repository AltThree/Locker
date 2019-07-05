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

namespace AltThree\Locker\Http\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

/**
 * This is the locked http exception class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class LockedHttpException extends HttpException
{
    /**
     * Create a new locked http exception instance.
     *
     * @param string|null     $message
     * @param \Throwable|null $previous
     * @param int             $code
     * @param array           $headers
     *
     * @return void
     */
    public function __construct(string $message = null, Throwable $previous = null, int $code = 0, array $headers = [])
    {
        parent::__construct(423, $message, $previous, $headers, $code);
    }
}
