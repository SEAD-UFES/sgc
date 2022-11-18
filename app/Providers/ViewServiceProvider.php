<?php

namespace App\Providers;

use App\Http\View\Composers\ApplicantFormComposer;
use App\Http\View\Composers\ApplicantIndexComposer;
use App\Http\View\Composers\BondFormComposer;
use App\Http\View\Composers\BondIndexComposer;
use App\Http\View\Composers\CourseFormComposer;
use App\Http\View\Composers\CourseIndexComposer;
use App\Http\View\Composers\DocumentBatchFormComposer;
use App\Http\View\Composers\DocumentFormComposer;
use App\Http\View\Composers\DocumentIndexComposer;
use App\Http\View\Composers\EmployeeFormComposer;
use App\Http\View\Composers\EmployeeIndexComposer;
use App\Http\View\Composers\HeaderComposer;
use App\Http\View\Composers\PoleIndexComposer;
use App\Http\View\Composers\ResponsibilityFormComposer;
use App\Http\View\Composers\ResponsibilityIndexComposer;
use App\Http\View\Composers\RoleFormComposer;
use App\Http\View\Composers\RoleIndexComposer;
use App\Http\View\Composers\UserFormComposer;
use App\Http\View\Composers\UserIndexComposer;
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
            ['applicant.create', 'applicant.review'],
            ApplicantFormComposer::class
        );

        View::composer(
            ['applicant.index'],
            ApplicantIndexComposer::class
        );

        View::composer(
            ['bond.create', 'bond.edit'],
            BondFormComposer::class
        );

        View::composer(
            ['bond.index'],
            BondIndexComposer::class
        );

        View::composer(
            ['document.create'],
            DocumentFormComposer::class
        );

        View::composer(
            ['document.index', 'reports.rightsIndex'],
            DocumentIndexComposer::class
        );

        View::composer(
            ['document.create-many-2'],
            DocumentBatchFormComposer::class
        );

        View::composer(
            ['course.create', 'course.edit'],
            CourseFormComposer::class
        );

        View::composer(
            ['course.index'],
            CourseIndexComposer::class
        );

        View::composer(
            ['employee.create', 'employee.edit', 'applicant.designate'],
            EmployeeFormComposer::class
        );

        View::composer(
            ['employee.index'],
            EmployeeIndexComposer::class
        );

        View::composer(
            ['pole.index'],
            PoleIndexComposer::class
        );

        View::composer(
            ['responsibility.create', 'responsibility.edit'],
            ResponsibilityFormComposer::class
        );

        View::composer(
            ['responsibility.index'],
            ResponsibilityIndexComposer::class
        );

        View::composer(
            ['role.create', 'role.edit'],
            RoleFormComposer::class
        );

        View::composer(
            ['role.index'],
            RoleIndexComposer::class
        );

        View::composer(
            ['user.create', 'user.edit'],
            UserFormComposer::class
        );

        View::composer(
            ['user.index'],
            UserIndexComposer::class
        );

        View::composer(
            ['layouts.partialHeader'],
            HeaderComposer::class
        );
    }
}
