<?php

namespace Zabaala\Moip;

use Illuminate\Support\ServiceProvider;

class MoipServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    public $defer = true;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot() {

        $configPath = __DIR__.'/config/moip.php';

        $this->publishes(
            [$configPath => config_path('moip.php')],
            'moip'
        );

        $this->mergeConfigFrom($configPath, 'moip');
    }

    /**
     * Register the service provider.
     */
    public function register() {
        
        $this->app->bind('moip', function($app){

            $authentication = new MoipBasicAuth(\Config::get('moip.api.token'), \Config::get('moip.api.key'));

            return new Moip($authentication, \Config::get('moip.endpoint'));
        });
        
        $this->app->alias('moip', 'Zabaala\Moip\Moip');
    }

    /**
     * @return array
     */
    public function provides()
    {
        return ['moip'];
    }
}