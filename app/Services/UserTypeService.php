<?php

namespace App\Services;

use App\Events\ModelListed;
use App\Models\UserType;
use Illuminate\Database\Eloquent\Collection;

class UserTypeService
{
    /**
     * Undocumented function
     *
     * @return Collection<int, UserType>
     */
    public function list(): Collection
    {
        ModelListed::dispatch(UserType::class);

        return UserType::orderBy('name')->get();
    }
}
