<?php

namespace App\Providers;

use App\Http\View\Composers\ApprovedFormComposer;
use App\Http\View\Composers\ApprovedIndexComposer;
use App\Http\View\Composers\BondDocumentBatchFormComposer;
use App\Http\View\Composers\BondDocumentFormComposer;
use App\Http\View\Composers\BondDocumentIndexComposer;
use App\Http\View\Composers\BondFormComposer;
use App\Http\View\Composers\BondIndexComposer;
use App\Http\View\Composers\CourseFormComposer;
use App\Http\View\Composers\CourseIndexComposer;
use App\Http\View\Composers\CourseTypeIndexComposer;
use App\Http\View\Composers\EmployeeDocumentBatchFormComposer;
use App\Http\View\Composers\EmployeeDocumentFormComposer;
use App\Http\View\Composers\EmployeeDocumentIndexComposer;
use App\Http\View\Composers\EmployeeFormComposer;
use App\Http\View\Composers\EmployeeIndexComposer;
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
            ['approved.create', 'approved.review'],
            ApprovedFormComposer::class
        );

        View::composer(
            ['approved.index'],
            ApprovedIndexComposer::class
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
            ['bond.document.create'],
            BondDocumentFormComposer::class
        );

        View::composer(
            ['bond.document.index', 'reports.rightsIndex'],
            BondDocumentIndexComposer::class
        );

        View::composer(
            ['bond.document.create-many-2'],
            BondDocumentBatchFormComposer::class
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
            ['coursetype.index'],
            CourseTypeIndexComposer::class
        );

        View::composer(
            ['employee.create', 'employee.edit'],
            EmployeeFormComposer::class
        );

        View::composer(
            ['employee.index'],
            EmployeeIndexComposer::class
        );

        View::composer(
            ['employee.document.create'],
            EmployeeDocumentFormComposer::class
        );

        View::composer(
            ['employee.document.index'],
            EmployeeDocumentIndexComposer::class
        );

        View::composer(
            ['employee.document.create-many-2'],
            EmployeeDocumentBatchFormComposer::class
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
    }
}
