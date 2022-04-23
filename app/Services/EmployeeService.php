<?php

namespace App\Services;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class EmployeeService
{
    /**
     * Undocumented function
     *
     * @return LengthAwarePaginator
     */
    public function list(): LengthAwarePaginator
    {
        (new Employee)->logListed();

        $query = Employee::with(['gender', 'birthState', 'documentType', 'maritalStatus', 'addressState', 'user']);
        $query = $query->AcceptRequest(Employee::$accepted_filters)->filter();
        $query = $query->sortable(['updated_at' => 'desc']);
        $employees = $query->paginate(10);
        $employees->withQueryString();

        return $employees;
    }

    /**
     * Undocumented function
     *
     * @param array $attributes
     * @return Employee
     */
    public function create(array $attributes): ?Employee
    {
        $employee = null;
        DB::transaction(function () use ($attributes, &$employee) {
            $employee = Employee::create($attributes);
            $this->userAttach($employee);
        });

        return $employee;
    }

    /**
     * Undocumented function
     *
     * @param Employee $employee
     * @return Employee
     */
    public function read(Employee $employee): Employee
    {
        $employee->logFetched($employee);

        return $employee;
    }

    /**
     * Undocumented function
     *
     * @param Employee $employee
     * @return void
     */
    private function userAttach(Employee $employee)
    {
        $existentUser = User::where('email', $employee->email)->doesntHave('employee')->first();

        if (!is_null($existentUser)) {
            $existentUser->employee_id = $employee->id;
            $existentUser->save();
        }
    }

    /**
     * Undocumented function
     *
     * @param array $attributes
     * @param Employee $employee
     * @return Employee
     */
    public function update(array $attributes, Employee $employee): ?Employee
    {
        DB::transaction(function () use ($attributes, $employee) {
            $employee->update($attributes);
            $this->userAttach($employee);
            return $employee;
        });

        return null;
    }

    /**
     * Undocumented function
     *
     * @param Employee $employee
     * @return void
     */
    public function delete(Employee $employee)
    {
        $employeeUser = $employee->user;

        DB::transaction(function () use ($employee, $employeeUser) {
            if (!is_null($employeeUser)) {
                $employeeUser->employee_id = null;
                $employeeUser->save();
            }

            foreach ($employee->courses as $course) {
                $course->bond->bondDocuments()->delete();
            }

            $employee->courses()->detach();
            $employee->employeeDocuments()->delete();
            $employee->delete();
        });
    }
}
