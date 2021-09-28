<?php

namespace App\Services;

use App\Models\User;
use App\Models\Employee;
use App\CustomClasses\SgcLogger;
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
        SgcLogger::writeLog(target: 'Employee', action: 'index');

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
    public function create(array $attributes): Employee
    {
        $employee = new Employee;

        $employee->name = $attributes['name'];
        $employee->cpf = $attributes['cpf'];
        $employee->job = $attributes['job'];
        $employee->gender_id = $attributes['genders'];
        $employee->birthday = $attributes['birthday'];
        $employee->birth_state_id = $attributes['birthStates'];
        $employee->birth_city = $attributes['birthCity'];
        $employee->id_number = $attributes['idNumber'];
        $employee->document_type_id = $attributes['documentTypes'];
        $employee->id_issue_date = $attributes['idIssueDate'];
        $employee->id_issue_agency = $attributes['idIssueAgency'];
        $employee->marital_status_id = $attributes['maritalStatuses'];
        $employee->spouse_name = $attributes['spouseName'];
        $employee->father_name = $attributes['fatherName'];
        $employee->mother_name = $attributes['motherName'];
        $employee->address_street = $attributes['addressStreet'];
        $employee->address_complement = $attributes['addressComplement'];
        $employee->address_number = $attributes['addressNumber'];
        $employee->address_district = $attributes['addressDistrict'];
        $employee->address_postal_code = $attributes['addressPostalCode'];
        $employee->address_state_id = $attributes['addressStates'];
        $employee->address_city = $attributes['addressCity'];
        $employee->area_code = $attributes['areaCode'];
        $employee->phone = $attributes['phone'];
        $employee->mobile = $attributes['mobile'];
        $employee->email = $attributes['email'];

        DB::transaction(function () use ($employee) {
            $employee->save();

            $this->userAttach($employee);
        });

        SgcLogger::writeLog(target: $employee, action: 'store');

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
            $existentUser->employee = $employee;
            SgcLogger::writeLog(target: $existentUser, action: 'Updating existent User with Employee info');
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
    public function update(array $attributes, Employee $employee): Employee
    {
        $employee->name = $attributes['name'];
        $employee->cpf = $attributes['cpf'];
        $employee->job = $attributes['job'];
        $employee->gender_id = $attributes['genders'];
        $employee->birthday = $attributes['birthday'];
        $employee->birth_state_id = $attributes['birthStates'];
        $employee->birth_city = $attributes['birthCity'];
        $employee->id_number = $attributes['idNumber'];
        $employee->document_type_id = $attributes['documentTypes'];
        $employee->id_issue_date = $attributes['idIssueDate'];
        $employee->id_issue_agency = $attributes['idIssueAgency'];
        $employee->marital_status_id = $attributes['maritalStatuses'];
        $employee->spouse_name = $attributes['spouseName'];
        $employee->father_name = $attributes['fatherName'];
        $employee->mother_name = $attributes['motherName'];
        $employee->address_street = $attributes['addressStreet'];
        $employee->address_complement = $attributes['addressComplement'];
        $employee->address_number = $attributes['addressNumber'];
        $employee->address_district = $attributes['addressDistrict'];
        $employee->address_postal_code = $attributes['addressPostalCode'];
        $employee->address_state_id = $attributes['addressStates'];
        $employee->address_city = $attributes['addressCity'];
        $employee->area_code = $attributes['areaCode'];
        $employee->phone = $attributes['phone'];
        $employee->mobile = $attributes['mobile'];
        $employee->email = $attributes['email'];

        SgcLogger::writeLog(target: $employee, action: 'update');

        DB::transaction(function () use ($employee) {
            $employee->save();

            $this->userAttach($employee);
        });

        return $employee;
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

        SgcLogger::writeLog(target: $employee, action: 'destroy');

        DB::transaction(function () use ($employee, $employeeUser) {
            if (!is_null($employeeUser)) {
                $employeeUser->employee = null;
                $employeeUser->save();
            }

            foreach ($employee->courses as $course) $course->bond->bondDocuments()->delete();

            $employee->courses()->detach();
            $employee->employeeDocuments()->delete();
            $employee->delete();
        });
    }
}
