<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

use App\Gates\GenericGates;
use App\Gates\UserGates;
use App\Gates\UserTypeAssignmentGates;
use App\Gates\PoleGates;
use App\Gates\CourseGates;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
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

        /* define a system admin user role */
        Gate::define('isAdm', function ($user) {
            return $user->userType->acronym == 'adm';
        });

        /* define a director user role */
        Gate::define('isDir', function ($user) {
            return $user->userType->acronym == 'dir';
        });

        /* define a grantor assistant user role */
        Gate::define('isAss', function ($user) {
            return $user->userType->acronym == 'ass';
        });

        /* define a academic secretary user role */
        Gate::define('isSec', function ($user) {
            return $user->userType->acronym == 'sec';
        });

        /* define a ldi user role */
        Gate::define('isLdi', function ($user) {
            return $user->userType->acronym == 'ldi';
        });

        /* define a grantee user role */
        Gate::define('isGra', function ($user) {
            return $user->userType->acronym == 'gra';
        });

        /* define a course coordinator user role */
        Gate::define('isCor', function ($user) {
            if ($user->employee == null)
                return false;

            return $user->employee->isCourseCoordinator();
        });

        GenericGates::define();
        UserTypeAssignmentGates::define();
        UserGates::define();
        PoleGates::define();
        CourseGates::define();
    }
}
