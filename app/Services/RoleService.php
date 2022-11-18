<?php

namespace App\Services;

use App\Events\ModelListed;
use App\Events\ModelRead;
use App\Helpers\TextHelper;
use App\Models\Role;
use App\Services\Dto\RoleDto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class RoleService
{
    /**
     * Undocumented function
     *
     * @return LengthAwarePaginator<Role>
     */
    public function list(): LengthAwarePaginator
    {
        ModelListed::dispatch(Role::class);

        $query = Role::select('id', 'name', 'description', 'grant_value', 'grant_type');
        $query = $query->AcceptRequest(Role::$acceptedFilters)->filter();
        $query = $query->sortable(['name' => 'asc']);

        $roles = $query->paginate(10);
        $roles->withQueryString();

        return $roles;
    }

    /**
     * Undocumented function
     *
     * @param RoleDto $storeRoleDto
     *
     * @return Role
     */
    public function create(RoleDto $storeRoleDto): Role
    {
        return Role::create([
            'name' => TextHelper::titleCase($storeRoleDto->name),
            'description' => TextHelper::titleCase($storeRoleDto->description),
            'grant_value' => $storeRoleDto->grantValue,
            'grant_type' => $storeRoleDto->grantType,
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
     * @param RoleDto $updateRoleDto
     * @param Role $role
     *
     * @return Role
     */
    public function update(RoleDto $updateRoleDto, Role $role): Role
    {
        $role->update([
            'name' => TextHelper::titleCase($updateRoleDto->name),
            'description' => TextHelper::titleCase($updateRoleDto->description),
            'grant_value' => $updateRoleDto->grantValue,
            'grant_type_id' => $updateRoleDto->grantType,
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
