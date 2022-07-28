<?php

namespace App\Gates;

use Illuminate\Support\Facades\Gate;

class EmployeeDocumentGates
{
    /**
     *
     * @return void
     */
    public static function define(): void
    {
        Gate::define('employeeDocument-list', static function ($user) {
            //who can do it (global).
            return Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global']);
        });

        Gate::define('employeeDocument-store', static function ($user) {
            //who can do it (global).
            return Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global']);
        });

        Gate::define('employeeDocument-download', static function ($user) {
            //who can do it (global).
            return Gate::forUser($user)->any(['isAdm-global', 'isDir-global', 'isAss-global', 'isSec-global']);
        });
    }
}
