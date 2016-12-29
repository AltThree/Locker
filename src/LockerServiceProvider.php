<?php

/*
 * This file is part of Alt Three Locker.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AltThree\Locker;

use AltThree\Locker\Connections\ConnectionInterface;
use AltThree\Locker\Connections\IlluminateConnection;
use AltThree\Locker\Connections\PredisConnection;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as LumenApplication;
use Predis\ClientInterface;

/**
 * This is the locker service provider class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class LockerServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->setupConfig();
    }

    /**
     * Setup the config.
     *
     * @return void
     */
    protected function setupConfig()
    {
        $source = realpath(__DIR__.'/../config/locker.php');

        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('locker.php')]);
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('locker');
        }

        $this->mergeConfigFrom($source, 'locker');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConnection();
        $this->registerLocker();
    }

    /**
     * Register the locker connection class.
     *
     * @return void
     */
    protected function registerConnection()
    {
        $this->app->singleton('locker.connection', function (Container $app) {
            $redis = $app['redis']->connection($app->config->get('locker.connection'));

            if ($redis instanceof ClientInterface) {
                return new PredisConnection($redis);
            }

            return new IlluminateConnection($redis);
        });

        $this->app->alias('locker.connection', ConnectionInterface::class);
    }

    /**
     * Register the locker class.
     *
     * @return void
     */
    protected function registerLocker()
    {
        $this->app->singleton('locker', function (Container $app) {
            $connection = $app['locker.connection'];

            return new Locker($connection);
        });

        $this->app->alias('locker', Locker::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            'locker',
            'locker.connection',
        ];
    }
}
