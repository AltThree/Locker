<?php

/*
 * This file is part of Alt Three Locker.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AltThree\Locker\Http\Middleware;

use AltThree\Locker\Exceptions\UnableToAcquireLockException;
use AltThree\Locker\Locker;
use Closure;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * This is the locking middleware class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class LockingMiddleware
{
    /**
     * The locker instance.
     *
     * @var \AltThree\Locker\Locker
     */
    protected $locker;

    /**
     * Create a new locking middleware instance.
     *
     * @param \AltThree\Locker\Locker $locker
     *
     * @return void
     */
    public function __construct(Locker $locker)
    {
        $this->locker = $locker;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @throws \Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->isMethodSafe()) {
            return $next($request);
        }

        $function = function () use ($request, $next) {
            return $next($request);
        };

        $name = 'session-'.$request->session()->getId();

        try {
            $response = $this->locker->execute($function, $name);
        } catch (UnableToAcquireLockException $e) {
            throw new UnprocessableEntityHttpException('Unable to acquire lock.');
        }

        return $response;
    }
}
