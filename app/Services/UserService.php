<?php

namespace App\Services;

use App\Models\User;
use App\Models\Employee;
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
        (new User)->logListed();

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
        $attributes['password'] = Hash::make($attributes['password']);
        $attributes['active'] = isset($attributes['active']);

        $user = User::create($attributes);

        $this->employeeAttach($user);

        return $user;
    }

    /**
     * Undocumented function
     * 
     * @param User $user
     * @return User
     */
    public function read(User $user): User
    {
        $user->logFetched($user);

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
        if (isset($attributes['password']) and $attributes['password'] != '')
            $attributes['password'] = Hash::make($attributes['password']);
        else
            unset($attributes['password']);
            
        $attributes['active'] = isset($attributes['active']);

        $user->update($attributes);

        $this->employeeAttach($user);

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
        $existentEmployee = $this->getEmployeeByEmail($user->email);

        if ($existentEmployee) {
            $user->employee_id = $existentEmployee->id;
            $user->save();
        }
    }

    /**
     * Undocumented function
     *
     * @param string $email
     * @return Employee
     */
    private function getEmployeeByEmail(string $email): ?Employee
    {
        $employee = Employee::where('email', $email)->first();
        return $employee;
    }

    /**
     * Undocumented function
     *
     * @param User $user
     * @return void
     */
    public function delete(User $user)
    {
        $user->delete();
    }
}
