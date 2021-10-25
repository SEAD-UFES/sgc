<?php

namespace App\Services;

use App\Models\UserType;
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
        (new UserType)->logListed();

        $userTypes = UserType::orderBy('name')->get();

        return $userTypes;
    }
}
