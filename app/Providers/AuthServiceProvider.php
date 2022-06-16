<?php

namespace App\Providers;

use App\Gates\ApprovedGates;
use App\Gates\BondDocumentGates;
use App\Gates\BondGates;
use App\Gates\CourseGates;
use App\Gates\EmployeeDocumentGates;
use App\Gates\EmployeeGates;
use App\Gates\GenericGates;
use App\Gates\PoleGates;
use App\Gates\RoleGates;
use App\Gates\UserGates;
use App\Gates\UserTypeAssignmentGates;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //basic function gates
        GenericGates::define();

        //model gates needs GenericGates;
        UserTypeAssignmentGates::define();
        UserGates::define();
        PoleGates::define();
        CourseGates::define();
        RoleGates::define();
        BondDocumentGates::define();
        BondGates::define();
        EmployeeDocumentGates::define();
        EmployeeGates::define();
        ApprovedGates::define();
    }
}
