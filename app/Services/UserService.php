<?php

namespace App\Services;

use App\Events\ModelListed;
use App\Events\ModelRead;
use App\Models\User;
use App\Services\Dto\StoreUserDto;
use App\Services\Dto\UpdateCurrentPasswordDto;
use App\Services\Dto\UpdateUserDto;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * Undocumented function
     *
     * @return LengthAwarePaginator
     */
    public function list(): LengthAwarePaginator
    {
        ModelListed::dispatch(User::class);

        $query = User::with(['employee']);
        $query = $query->AcceptRequest(User::$accepted_filters)->filter();
        $query = $query->sortable(['updated_at' => 'desc']);

        $users = $query->paginate(10);
        $users->withQueryString();

        return $users;
    }

    /**
     * Undocumented function
     *
     * @param StoreUserDto $storeUserDto
     *
     * @return User
     */
    public function create(StoreUserDto $storeUserDto): User
    {
        return User::create([
            'email' => mb_strtolower($storeUserDto->email),
            'password' => Hash::make($storeUserDto->password),
            'active' => $storeUserDto->active,
            'employee_id' => $storeUserDto->employeeId,
        ]);
    }

    /**
     * Undocumented function
     *
     * @param User $user
     *
     * @return User
     */
    public function read(User $user): User
    {
        ModelRead::dispatch($user);

        return $user;
    }

    /**
     * Undocumented function
     *
     * @param UpdateUserDto $updateUserDto
     * @param User $user
     *
     * @return User
     */
    public function update(UpdateUserDto $updateUserDto, User $user): User
    {
        $user->update([
            'email' => mb_strtolower($updateUserDto->email),
            'password' => $updateUserDto->password !== '' ? Hash::make($updateUserDto->password) : $user->password,
            'active' => $updateUserDto->active,
            'employee_id' => $updateUserDto->employeeId,
        ]);

        return $user;
    }

    /**
     * Undocumented function
     *
     * @param UpdateCurrentPasswordDto $updateCurrentPasswordDto
     * @param User $user
     *
     * @return User
     */
    public function updateCurrentPassword(UpdateCurrentPasswordDto $updateCurrentPasswordDto, User $user): User
    {
        if ($updateCurrentPasswordDto->password === null || $updateCurrentPasswordDto->password === '') {
            throw new Exception('A senha não pode ser vazia');
        }

        $user->update([
            'password' => Hash::make($updateCurrentPasswordDto->password),
        ]);

        return $user;
    }

    /**
     * Undocumented function
     *
     * @param User $user
     *
     * @return void
     */
    public function delete(User $user)
    {
        $user->delete();
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function unlinkEmployee(User $user): void
    {
        $user->unlinkEmployee();
        $user->save();
    }
}
