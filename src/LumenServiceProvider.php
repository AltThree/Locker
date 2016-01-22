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

use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as Application;

/**
 * This is the locker service provider class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class LumenServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->setupConfig($this->app);
    }

    /**
     * Setup the config.
     *
     * @param \Laravel\Lumen\Application $app
     *
     * @return void
     */
    protected function setupConfig(Application $app)
    {
        $source = realpath(__DIR__.'/../config/locker.php');

        $app->configure('locker');

        $this->mergeConfigFrom($source, 'locker');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerLocker($this->app);
    }

    /**
     * Register the locker class.
     *
     * @param \Laravel\Lumen\Application $app
     *
     * @return void
     */
    protected function registerLocker(Application $app)
    {
        $app->singleton('locker', function (Application $app) {
            $redis = $app['redis']->connection($app->config->get('locker.connection'));

            return new Locker($redis);
        });

        $app->alias('locker', Locker::class);
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
        ];
    }
}
