<?php

namespace App\Services;

use App\Models\Approved;
use App\Models\Employee;
use App\CustomClasses\SgcLogger;
use App\Imports\ApprovedsImport;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Exceptions\EmployeeAlreadyExistsException;

class ApprovedService
{
    /**
     * Undocumented function
     *
     * @return LengthAwarePaginator
     */
    public function list(): LengthAwarePaginator
    {
        SgcLogger::writeLog(target: 'Approved', action: 'index');

        $query = Approved::with(['approvedState', 'course', 'pole', 'role']);
        $query = $query->AcceptRequest(Approved::$accepted_filters)->filter();
        $query = $query->sortable(['updated_at' => 'desc']);
        $approveds = $query->paginate(10);
        $approveds->withQueryString();

        return $approveds;
    }

    /**
     * Undocumented function
     *
     * @param Approved $approved
     * @return void
     */
    public function delete(Approved $approved): void
    {
        SgcLogger::writeLog(target: $approved, action: 'destroy');

        $approved->delete();
    }

    /**
     * Undocumented function
     *
     * @param array $attributes
     * @param Approved $approved
     * @return void
     */
    public function changeState(array $attributes, Approved $approved): void
    {
        $new_state_id = $attributes['states'];
        $approved->approved_state_id = $new_state_id;

        SgcLogger::writeLog(target: $approved, action: 'edit');

        $approved->save();
    }

    /**
     * Undocumented function
     *
     * @param array $attributes
     * @param Approved $approved
     * @return Employee
     */
    public function designate(array $attributes, Approved $approved): Employee
    {
        if ($this->employeeAlreadyExists($approved))
            throw new EmployeeAlreadyExistsException("Employee already exists", 1);

        $employee = new Employee;
        $employee->name = $approved->name;
        $employee->email = $approved->email;
        $employee->area_code = $approved->area_code;
        $employee->phone = $approved->phone;
        $employee->mobile = $approved->mobile;

        SgcLogger::writeLog(target: $approved, action: 'designate');

        $this->delete($approved);

        return $employee;
    }

    /**
     * Undocumented function
     *
     * @param Approved $approved
     * @return boolean
     */
    public function employeeAlreadyExists(Approved $approved): bool
    {
        $existantEmployee = Employee::where('email', $approved->email)->first();
        if (is_null($existantEmployee))
            return false;

        return true;
    }

    /**
     * Undocumented function
     *
     * @param UploadedFile $file
     * @return Collection
     */
    public function importFile(UploadedFile $file): Collection
    {
        $approveds = collect();

        SgcLogger::writeLog(target: 'Approved', action: 'import');

        $fileName = $file->getClientOriginalName();
        $filePath = $file->storeAs('temp', $fileName, 'local');

        Excel::import(new ApprovedsImport($approveds), $filePath);
        Storage::delete($filePath);

        return $approveds;
    }
    
    /**
     * Undocumented function
     *
     * @param array $attributes
     * @return void
     */
    public function massStore(array $attributes): void
    {
        SgcLogger::writeLog(target: 'Mass Approveds', action: 'create');

        DB::transaction(function () use ($attributes) {

            $approvedsCount = $attributes['approvedsCount'];
            for ($i = 0; $i < $approvedsCount; $i++) {
                if (isset($attributes['check_' . $i])) {
                    $approved = new Approved();
                    $approved->name = $attributes['name_' . $i];
                    $approved->email = $attributes['email_' . $i];
                    $approved->area_code = $attributes['area_' . $i];
                    $approved->phone = $attributes['phone_' . $i];
                    $approved->mobile = $attributes['mobile_' . $i];
                    $approved->announcement = $attributes['announcement_' . $i];
                    $approved->course_id = $attributes['courses_' . $i];
                    $approved->role_id = $attributes['roles_' . $i];
                    $approved->pole_id = $attributes['poles_' . $i];
                    $approved->approved_state_id = 1;
                    $approved->save();
                }
            }
        });
    }
}
