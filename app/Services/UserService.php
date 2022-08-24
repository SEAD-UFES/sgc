<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\User;
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
        (new User())->logListed();

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
     * @param array<string, string> $attributes
     *
     * @return User
     */
    public function create(array $attributes): User
    {
        $attributes['email'] = mb_strtolower($attributes['email']);

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
     *
     * @return User
     */
    public function read(User $user): User
    {
        $user->logFetched();

        return $user;
    }

    /**
     * Undocumented function
     *
     * @param array<string, string> $attributes
     * @param User $user
     *
     * @return User
     */
    public function update(array $attributes, User $user): User
    {
        $attributes['email'] = mb_strtolower($attributes['email']);

        if (isset($attributes['password']) && $attributes['password'] !== '') {
            $attributes['password'] = Hash::make($attributes['password']);
        } else {
            unset($attributes['password']);
        }

        $attributes['active'] = isset($attributes['active']);

        $user->update($attributes);

        $this->employeeAttach($user);

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
     * @param array<string, string> $attributes
     * @param User $user
     *
     * @return void
     */
    public function linkEmployee(array $attributes, User $user): void
    {
        /**
         * @var Employee $employee
         */
        $employee = Employee::find($attributes['employee_id']);

        $user->linkEmployee($employee);
        $user->save();
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

    /**
     * Undocumented function
     *
     * @param User $user
     *
     * @return void
     */
    private function employeeAttach(User $user)
    {
        $existentEmployee = $this->getEmployeeByEmail($user->email);

        if ($existentEmployee !== null) {
            $user->employee_id = $existentEmployee->id;
            $user->save();
        }
    }

    /**
     * Undocumented function
     *
     * @param string $email
     *
     * @return Employee
     */
    private function getEmployeeByEmail(string $email): ?Employee
    {
        return Employee::where('email', $email)->first();
    }
}
