<?php

namespace App\Services;

use App\Events\ModelListed;
use App\Events\ModelRead;
use App\Helpers\TextHelper;
use App\Models\Bond;
use App\Models\Course;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class EmployeeService
{
    /**
     * Undocumented function
     *
     * @return LengthAwarePaginator
     */
    public function list(): LengthAwarePaginator
    {
        ModelListed::dispatch(Employee::class);

        $query = Employee::with(['birthState', 'documentType', 'addressState', 'user']);
        $query = $query->AcceptRequest(Employee::$accepted_filters)->filter();
        $query = $query->sortable(['updated_at' => 'desc']);

        $employees = $query->paginate(10);
        $employees->withQueryString();

        return $employees;
    }

    /**
     * Undocumented function
     *
     * @param array<string, string> $attributes
     *
     * @return Employee
     */
    public function create(array $attributes): ?Employee
    {
        $attributes = Arr::map($attributes, static function ($value, $key) {
            switch ($key) {
                case 'email':
                    return mb_strtolower($value);
                case 'id_issue_agency':
                    return mb_strtoupper($value);
                case 'marital_status':
                    return $value;
                default:
                    return TextHelper::titleCase($value);
            }
        });

        $attributes = Arr::map($attributes, static function ($value, $key) {
            return $value === '' ? null : $value;
        });

        $employee = null;
        DB::transaction(function () use ($attributes, &$employee) {
            $employee = Employee::create($attributes);
            $this->userAttach($employee);

            $employee->bankAccount()->create([
                'bank_name' => TextHelper::titleCase($attributes['bank_name']),
                'agency_number' => $attributes['agency_number'],
                'account_number' => $attributes['account_number'],
            ]);
        });

        return $employee;
    }

    /**
     * Undocumented function
     *
     * @param Employee $employee
     *
     * @return Employee
     */
    public function read(Employee $employee): Employee
    {
        ModelRead::dispatch($employee);

        return $employee;
    }

    /**
     * Undocumented function
     *
     * @param array<string, string> $attributes
     * @param Employee $employee
     *
     * @return Employee
     */
    public function update(array $attributes, Employee $employee): ?Employee
    {
        $attributes = Arr::map($attributes, static function ($value, $key) {
            switch ($key) {
                case 'email':
                    return mb_strtolower($value);
                case 'id_issue_agency':
                    return mb_strtoupper($value);
                case 'marital_status':
                    return $value;
                default:
                    return TextHelper::titleCase($value);
            }
        });

        $attributes = Arr::map($attributes, static function ($value, $key) {
            return $value === '' ? null : $value;
        });

        DB::transaction(function () use ($attributes, $employee) {
            $employee->update($attributes);
            $this->userAttach($employee);

            $employee->bankAccount()->updateOrCreate([], [
                'bank_name' => TextHelper::titleCase($attributes['bank_name']),
                'agency_number' => $attributes['agency_number'],
                'account_number' => $attributes['account_number'],
            ]);

            return $employee;
        });

        return null;
    }

    /**
     * Undocumented function
     *
     * @param Employee $employee
     *
     * @return void
     */
    public function delete(Employee $employee): void
    {
        $employeeUser = $employee->user;

        DB::transaction(static function () use ($employee, $employeeUser) {
            if (! is_null($employeeUser)) {
                $employeeUser->employee_id = null;
                $employeeUser->save();
            }

            $employee->bankAccount()->delete();

            /**
             * @var Collection<int, Course> $employeeCourses
             */
            $employeeCourses = $employee->courses;

            foreach ($employeeCourses as $employeeCourse) {
                /**
                 * @var Collection<int, Bond> $employeeCourseBonds
                 */
                $employeeCourseBonds = $employeeCourse->bonds;

                foreach ($employeeCourseBonds as $employeeCourseBond) {
                    $employeeCourseBond->bondDocuments()->delete();
                }
            }

            $employee->courses()->detach();
            $employee->employeeDocuments()->delete();
            $employee->delete();
        });
    }

    /**
     * Undocumented function
     *
     * @param Employee $employee
     *
     * @return void
     */
    private function userAttach(Employee $employee)
    {
        $existentUser = User::where('email', $employee->email)->doesntHave('employee')->first();

        if (! is_null($existentUser)) {
            $existentUser->employee_id = $employee->id;
            $existentUser->save();
        }
    }
}
