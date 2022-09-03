<?php

namespace App\Providers;

use App\Logging\Logger;
use App\Logging\LoggerInterface;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * All of the container bindings that should be registered.
     *
     * @var array<string, string>
     */
    public $bindings = [
        LoggerInterface::class => Logger::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (App::class::Environment() === 'production') {
            $this->app['request']->server->set('HTTPS', true);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
    }
}
