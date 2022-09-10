<?php

namespace App\Providers;

use App\Http\View\Composers\ApprovedFormComposer;
use App\Http\View\Composers\BondFormComposer;
use App\Http\View\Composers\CourseFormComposer;
use App\Http\View\Composers\EmployeeFormComposer;
use App\Http\View\Composers\ResponsibilityFormComposer;
use App\Http\View\Composers\RoleFormComposer;
use App\Http\View\Composers\UserFormComposer;
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
            ['approved.create', 'approved.review'],
            ApprovedFormComposer::class
        );

        View::composer(
            ['bond.create', 'bond.edit'],
            BondFormComposer::class
        );

        View::composer(
            ['course.create', 'course.edit'],
            CourseFormComposer::class
        );

        View::composer(
            ['employee.create', 'employee.edit'],
            EmployeeFormComposer::class
        );

        View::composer(
            ['responsibility.create', 'responsibility.edit'],
            ResponsibilityFormComposer::class
        );

        View::composer(
            ['role.create', 'role.edit'],
            RoleFormComposer::class
        );

        View::composer(
            ['user.create', 'user.edit'],
            UserFormComposer::class
        );
    }
}
