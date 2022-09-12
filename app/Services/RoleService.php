<?php

namespace App\Services;

use App\Events\ModelListed;
use App\Events\ModelRead;
use App\Helpers\TextHelper;
use App\Models\Role;
use App\Services\Dto\StoreRoleDto;
use App\Services\Dto\UpdateRoleDto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class RoleService
{
    /**
     * Undocumented function
     *
     * @return LengthAwarePaginator
     */
    public function list(): LengthAwarePaginator
    {
        ModelListed::dispatch(Role::class);

        $roles_query = new Role();
        $roles_query = $roles_query->AcceptRequest(Role::$accepted_filters)->filter();
        $roles_query = $roles_query->sortable(['name' => 'asc']);

        $roles = $roles_query->paginate(10);
        $roles->withQueryString();

        return $roles;
    }

    /**
     * Undocumented function
     *
     * @param StoreRoleDto $storeRoleDto
     *
     * @return Role
     */
    public function create(StoreRoleDto $storeRoleDto): Role
    {
        return Role::create([
            'name' => TextHelper::titleCase($storeRoleDto->name),
            'description' => TextHelper::titleCase($storeRoleDto->description),
            'grant_value' => $storeRoleDto->grantValue,
            'grant_type_id' => $storeRoleDto->grantTypeId,
        ]);
    }

    /**
     * Undocumented function
     *
     * @param Role $role
     *
     * @return Role
     */
    public function read(Role $role): Role
    {
        ModelRead::dispatch($role);

        return $role;
    }

    /**
     * Undocumented function
     *
     * @param UpdateRoleDto $updateRoleDto
     * @param Role $role
     *
     * @return Role
     */
    public function update(UpdateRoleDto $updateRoleDto, Role $role): Role
    {
        $role->update([
            'name' => TextHelper::titleCase($updateRoleDto->name),
            'description' => TextHelper::titleCase($updateRoleDto->description),
            'grant_value' => $updateRoleDto->grantValue,
            'grant_type_id' => $updateRoleDto->grantTypeId,
        ]);

        return $role;
    }

    /**
     * Undocumented function
     *
     * @param Role $role
     *
     * @return void
     */
    public function delete(Role $role)
    {
        $role->delete();
    }
}
