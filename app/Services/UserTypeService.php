<?php

namespace App\Services;

use App\Models\UserType;
use App\CustomClasses\SgcLogger;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class UserTypeService
{
    /**
     * Undocumented function
     *
     * @return Collection
     */
    public function list(): Collection
    {
        //SgcLogger::writeLog(target: 'UserType', action: 'index');

        $userTypes = UserType::orderBy('name')->get();

        return $userTypes;
    }
}
