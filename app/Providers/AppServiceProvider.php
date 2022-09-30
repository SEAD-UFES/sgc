<?php

namespace App\Providers;

use App\Interfaces\BondDocumentRepositoryInterface;
use App\Interfaces\EmployeeDocumentRepositoryInterface;
use App\Logging\Logger;
use App\Logging\LoggerInterface;
use App\Repositories\BondDocumentRepository;
use App\Repositories\EmployeeDocumentRepository;
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
        EmployeeDocumentRepositoryInterface::class => EmployeeDocumentRepository::class,
        BondDocumentRepositoryInterface::class => BondDocumentRepository::class,
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
