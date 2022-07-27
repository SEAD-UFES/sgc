<?php

namespace App\Gates;

use Illuminate\Support\Facades\Gate;

class EmployeeDocumentGates
{
    public static function define()
    {
        Gate::define('employeeDocument-list', static function ($user) {
            //who can do it (global).
            if (Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global'])) {
                return true;
            }
            //no permission
            return false;
        });

        Gate::define('employeeDocument-store', static function ($user) {
            //who can do it (global).
            if (Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global'])) {
                return true;
            }
            //no permission
            return false;
        });

        Gate::define('employeeDocument-download', static function ($user) {
            //who can do it (global).
            if (Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global'])) {
                return true;
            }
            //no permission
            return false;
        });
    }
}
