<?php

namespace App\Services;

use App\Models\Approved;
use App\Models\Employee;
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
        (new Approved)->logListed();

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
     * @return Approved
     */
    public function read(Approved $approved): Approved
    {
        $approved->logFetched($approved);

        return $approved;
    }

    /**
     * Undocumented function
     *
     * @param Approved $approved
     * @return void
     */
    public function delete(Approved $approved): void
    {
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

        $approved->save();
    }

    /**
     * Undocumented function
     *
     * @param Approved $approved
     * @return Employee
     */
    public function designate(Approved $approved): Employee
    {
        if ($this->employeeEmailAlreadyExists($approved))
            throw new EmployeeAlreadyExistsException("Employee email already exists", 1);

        $employee = new Employee;
        $employee->name = $approved->name;
        $employee->email = $approved->email;
        $employee->area_code = $approved->area_code;
        $employee->phone = $approved->phone;
        $employee->mobile = $approved->mobile;

        //$this->delete($approved);

        return $employee;
    }

    /**
     * Undocumented function
     *
     * @param Approved $approved
     * @return boolean
     */
    public function employeeEmailAlreadyExists(Approved $approved): bool
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
    public function importApproveds(UploadedFile $file): Collection
    {

        $filePath = $this->getFilePath($file);

        $approveds = $this->getApprovedsFromFile($filePath);
        Storage::delete($filePath);

        return $approveds;
    }

    /**
     * Undocumented function
     *
     * @param UploadedFile $file
     * @return string
     */
    protected function getFilePath(UploadedFile $file): string
    {
        $fileName = $file->getClientOriginalName();

        return $file->storeAs('temp', $fileName, 'local');
    }

    /**
     * Undocumented function
     *
     * @param string $filePath
     * @return Collection
     */
    protected function getApprovedsFromFile(string $filePath): Collection
    {
        $approveds = collect();
        Excel::import(new ApprovedsImport($approveds), $filePath);

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

        $attributes = collect($attributes);

        DB::transaction(function () use ($attributes) {

            $approvedsCount = $attributes->get('approvedsCount');

            for ($i = 0; $i < $approvedsCount; $i++) {
                if ($attributes->get('check_' . $i)) {
                    $approved = new Approved();
                    $approved->name = $attributes->get('name_' . $i);
                    $approved->email = $attributes->get('email_' . $i);
                    $approved->area_code = $attributes->get('area_' . $i);
                    $approved->phone = $attributes->get('phone_' . $i);
                    $approved->mobile = $attributes->get('mobile_' . $i);
                    $approved->announcement = $attributes->get('announcement_' . $i);
                    $approved->course_id = $attributes->get('courses_' . $i);
                    $approved->role_id = $attributes->get('roles_' . $i);
                    $approved->pole_id = $attributes->get('poles_' . $i);
                    $approved->approved_state_id = 1;
                    $approved->save();
                }
            }
        });
    }
}
