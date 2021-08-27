<?php

namespace App\Gates;

use Illuminate\Support\Facades\Gate;

class EmployeeDocumentGates
{
    public static function define()
    {
        Gate::define('employeeDocument-list', function ($user) {
            //who can do it (global).
            if (Gate::forUser($user)->any(['is-Adm-global', 'is-Dir-global', 'is-Ass-global', 'is-Sec-global'])) return true;

            //no permission
            return false;
        });

        Gate::define('employeeDocument-store', function ($user) {
            //who can do it (global).
            if (Gate::forUser($user)->any(['is-Adm-global', 'is-Dir-global', 'is-Ass-global', 'is-Sec-global'])) return true;

            //no permission
            return false;
        });

        Gate::define('employeeDocument-download', function ($user) {
            //who can do it (global).
            if (Gate::forUser($user)->any(['is-Adm-global', 'is-Dir-global', 'is-Ass-global', 'is-Sec-global'])) return true;

            //no permission
            return false;
        });
    }
}
