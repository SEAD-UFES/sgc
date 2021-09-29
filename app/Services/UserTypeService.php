<?php

namespace App\Services;

use App\Models\UserType;
use App\CustomClasses\SgcLogger;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserTypeService
{
    /**
     * Undocumented function
     *
     * @return LengthAwarePaginator
     */
    public function list(): LengthAwarePaginator
    {
        SgcLogger::writeLog(target: 'UserType', action: 'index');

        $userTypes = UserType::orderBy('name')->all();

        return $userTypes;
    }
}
