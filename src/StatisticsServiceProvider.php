<?php

namespace Aphly\LaravelStatistics;

use Aphly\Laravel\Providers\ServiceProvider;

class StatisticsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */

    public function register()
    {
		$this->mergeConfigFrom(
            __DIR__.'/config/statistics.php', 'statistics'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/statistics.php' => config_path('statistics.php'),
            __DIR__.'/config/ipv4.sql' => storage_path('app/private/ipv4.sql'),
            __DIR__.'/public' => public_path('static/statistics')
        ]);
        //$this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->loadViewsFrom(__DIR__.'/views', 'laravel-statistics');
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
    }

}
