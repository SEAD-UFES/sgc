<?php

namespace App\Providers;

use App\Http\View\Composers\EmployeeCreateComposer;
use App\Http\View\Creators\EmployeeCreateCreator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        View::composer(
            ['employee.create', 'employee.edit'],
            EmployeeCreateComposer::class
        );
    }
}
