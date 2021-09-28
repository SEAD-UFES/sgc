<?php

namespace App\Services;

use App\Models\User;
use App\Models\Employee;
use App\CustomClasses\SgcLogger;
use Illuminate\Support\Facades\Hash;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService
{
    /**
     * Undocumented function
     *
     * @return LengthAwarePaginator
     */
    public function list(): LengthAwarePaginator
    {
        SgcLogger::writeLog(target: 'User', action: 'index');

        $query = User::with(['userType', 'employee']);
        $query = $query->AcceptRequest(User::$accepted_filters)->filter();
        $query = $query->sortable(['updated_at' => 'desc']);
        $users = $query->paginate(10);
        $users->withQueryString();

        return $users;
    }

    /**
     * Undocumented function
     *
     * @param array $attributes
     * @return User
     */
    public function create(array $attributes): User
    {
        $user = new User;

        $user->email = $attributes['email'];
        $user->password = Hash::make($attributes['password']);
        $user->active = isset($attributes['active']);

        $this->employeeAttach($user);

        $user->save();

        SgcLogger::writeLog(target: $user, action: 'store');

        return $user;
    }

    /**
     * Undocumented function
     *
     * @param array $attributes
     * @param User $user
     * @return User
     */
    public function update(array $attributes, User $user): User
    {
        $user->email = $attributes['email'];

        if ($attributes['password'] != '')
            $user->password = Hash::make($attributes['password']);

        $user->active = isset($attributes['active']);

        $this->employeeAttach($user);

        SgcLogger::writeLog(target: $user, action: 'update');
        
        $user->save();

        return $user;
    }

    /**
     * Undocumented function
     *
     * @param User $user
     * @return void
     */
    private function employeeAttach(User $user)
    {
        $existentEmployee = Employee::where('email', $user->email)->first();

        if (!is_null($existentEmployee)) {
            $user->employee_id = $existentEmployee->id;

            SgcLogger::writeLog(target: $existentEmployee, action: 'Updated existent Employee info on User');
        }
    }

    /**
     * Undocumented function
     *
     * @param User $user
     * @return void
     */
    public function delete(User $user)
    {
        SgcLogger::writeLog(target: $user);

        $user->delete();
    }
}
