<?php

namespace App\Services;

use App\Events\ModelListed;
use App\Events\ModelRead;
use App\Models\User;
use App\Services\Dto\UpdateCurrentPasswordDto;
use App\Services\Dto\UserDto;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * Undocumented function
     *
     * @return LengthAwarePaginator<User>
     */
    public function list(): LengthAwarePaginator
    {
        ModelListed::dispatch(User::class);

        $query = User::with(['employee']);
        $query = $query->AcceptRequest(User::$acceptedFilters)->filter();
        $query = $query->sortable(['updated_at' => 'desc']);

        $users = $query->paginate(10);
        $users->withQueryString();

        return $users;
    }

    /**
     * Undocumented function
     *
     * @param UserDto $storeUserDto
     *
     * @return User
     */
    public function create(UserDto $storeUserDto): User
    {
        return User::create([
            'login' => mb_strtolower($storeUserDto->login),
            'password' => Hash::make($storeUserDto->password),
            'active' => $storeUserDto->active,
            'employee_id' => $storeUserDto->employeeId > 0 ? $storeUserDto->employeeId : null,
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
     * @param UserDto $updateUserDto
     * @param User $user
     *
     * @return User
     */
    public function update(UserDto $updateUserDto, User $user): User
    {
        $user->update([
            'login' => mb_strtolower($updateUserDto->login),
            'password' => $updateUserDto->password !== '' ? Hash::make($updateUserDto->password) : $user->password,
            'active' => $updateUserDto->active,
            'employee_id' => $updateUserDto->employeeId > 0 ? $updateUserDto->employeeId : null,
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
        if ($updateCurrentPasswordDto->password == null || trim($updateCurrentPasswordDto->password) === '') {
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
        $user->employee()->dissociate();
        $user->save();
    }
}
