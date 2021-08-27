<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

use App\Gates\GenericGates;
use App\Gates\UserGates;
use App\Gates\UserTypeAssignmentGates;
use App\Gates\PoleGates;
use App\Gates\CourseGates;
use App\Gates\RoleGates;
use App\Gates\BondDocumentGates;
use App\Gates\BondGates;
use App\Gates\EmployeeDocumentGates;
use App\Gates\EmployeeGates;


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
            //return $user->userType->acronym == 'adm';

            //need to have session UserTypeAssignment active.
            $currentUTA = session('sessionUser')->getCurrentUTA();
            if (!$currentUTA) return false;

            //if currentUTA (UserTypeAssignment) is adm, ok
            $acronym_adm = $currentUTA->userType->acronym === 'adm';
            if ($acronym_adm) return true;

            //if no permission
            return false;
        });

        /* define a director user role */
        Gate::define('isDir', function ($user) {
            //return $user->userType->acronym == 'dir';

            //need to have session UserTypeAssignment active.
            $currentUTA = session('sessionUser')->getCurrentUTA();
            if (!$currentUTA) return false;

            //if currentUTA (UserTypeAssignment) is dir, ok
            $acronym_dir = $currentUTA->userType->acronym === 'dir';
            if ($acronym_dir) return true;

            //if no permission
            return false;
        });

        /* define a grantor assistant user role */
        Gate::define('isAss', function ($user) {
            //return $user->userType->acronym == 'ass';

            //need to have session UserTypeAssignment active.
            $currentUTA = session('sessionUser')->getCurrentUTA();
            if (!$currentUTA) return false;

            //if currentUTA (UserTypeAssignment) is ass, ok
            $acronym_ass = $currentUTA->userType->acronym === 'ass';
            if ($acronym_ass) return true;

            //if no permission
            return false;
        });

        /* define a academic secretary user role */
        Gate::define('isSec', function ($user) {
            //return $user->userType->acronym == 'sec';

            //need to have session UserTypeAssignment active.
            $currentUTA = session('sessionUser')->getCurrentUTA();
            if (!$currentUTA) return false;

            //if currentUTA (UserTypeAssignment) is sec, ok
            $acronym_sec = $currentUTA->userType->acronym === 'sec';
            if ($acronym_sec) return true;

            //if no permission
            return false;
        });

        /* define a ldi user role */
        Gate::define('isLdi', function ($user) {
            //return $user->userType->acronym == 'ldi';
            //need to have session UserTypeAssignment active.
            $currentUTA = session('sessionUser')->getCurrentUTA();
            if (!$currentUTA) return false;

            //if currentUTA (UserTypeAssignment) is gra, ok
            $acronym_ldi = $currentUTA->userType->acronym === 'ldi';
            if ($acronym_ldi) return true;

            //if no permission
            return false;
        });

        /* define a grantee user role */
        Gate::define('isCoord', function ($user) {
            //return $user->userType->acronym == 'gra';

            //need to have session UserTypeAssignment active.
            $currentUTA = session('sessionUser')->getCurrentUTA();
            if (!$currentUTA) return false;

            //if currentUTA (UserTypeAssignment) is gra, ok
            $acronym_gra = $currentUTA->userType->acronym === 'coord';
            if ($acronym_gra) return true;

            //if no permission
            return false;
        });

        //basic function gates
        GenericGates::define();
        //this gates need GenericGates::define();
        UserTypeAssignmentGates::define();
        UserGates::define();
        PoleGates::define();
        CourseGates::define();
        RoleGates::define();
        BondDocumentGates::define();
        BondGates::define();
        EmployeeDocumentGates::define();
        EmployeeGates::define();
    }
}
