<?php

namespace App\Services;

use App\Models\UserType;
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
        (new UserType())->logListed();

        return UserType::orderBy('name')->get();
    }
}
