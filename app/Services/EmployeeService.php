<?php

namespace App\Services;

use App\Events\ModelListed;
use App\Events\ModelRead;
use App\Helpers\TextHelper;
use App\Interfaces\EmployeeDocumentRepositoryInterface;
use App\Models\Bond;
use App\Models\Course;
use App\Models\Employee;
use App\Models\User;
use App\Services\Dto\StoreEmployeeDto;
use App\Services\Dto\UpdateEmployeeDto;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

class EmployeeService
{
    public function __construct(private EmployeeDocumentRepositoryInterface $documentRepository)
    {
    }

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
     * @param StoreEmployeeDto $storeEmployeeDto
     *
     * @return mixed
     */
    public function create(StoreEmployeeDto $storeEmployeeDto): mixed
    {
        return DB::transaction(static function () use ($storeEmployeeDto) {
            $employee = new Employee([
                'name' => TextHelper::titleCase($storeEmployeeDto->name),
                'cpf' => $storeEmployeeDto->cpf,
                'job' => TextHelper::titleCase($storeEmployeeDto->job),
                'gender' => $storeEmployeeDto->gender,
                'birthday' => $storeEmployeeDto->birthday,
                'birth_state_id' => $storeEmployeeDto->birthStateId,
                'birth_city' => TextHelper::titleCase($storeEmployeeDto->birthCity),
                'document_type_id' => $storeEmployeeDto->documentTypeId,
                'id_number' => $storeEmployeeDto->idNumber,
                'id_issue_date' => $storeEmployeeDto->idIssueDate,
                'id_issue_agency' => mb_strtoupper($storeEmployeeDto->idIssueAgency),
                'marital_status' => $storeEmployeeDto->maritalStatus,
                'spouse_name' => TextHelper::titleCase($storeEmployeeDto->spouseName),
                'father_name' => TextHelper::titleCase($storeEmployeeDto->fatherName),
                'mother_name' => TextHelper::titleCase($storeEmployeeDto->motherName),
                'address_street' => TextHelper::titleCase($storeEmployeeDto->addressStreet),
                'address_complement' => TextHelper::titleCase($storeEmployeeDto->addressComplement),
                'address_number' => $storeEmployeeDto->addressNumber,
                'address_district' => TextHelper::titleCase($storeEmployeeDto->addressDistrict),
                'address_postal_code' => $storeEmployeeDto->addressPostalCode,
                'address_state_id' => $storeEmployeeDto->addressStateId,
                'address_city' => TextHelper::titleCase($storeEmployeeDto->addressCity),
                'area_code' => $storeEmployeeDto->areaCode,
                'phone' => $storeEmployeeDto->phone,
                'mobile' => $storeEmployeeDto->mobile,
                'email' => mb_strtolower($storeEmployeeDto->email),
            ]);
            $employee->save();
            $employee->bankAccount()->create([
                'bank_name' => TextHelper::titleCase($storeEmployeeDto->bankName),
                'agency_number' => $storeEmployeeDto->agencyNumber,
                'account_number' => $storeEmployeeDto->accountNumber,
            ]);
        });
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

        $employee->setAttribute('documents', $this->documentRepository->getByEmployeeId($employee->id));
        $employee->setAttribute('activeBonds', $employee->bonds()->active()->orderBy('begin', 'ASC')->get());

        $activity = $this->getActivity($employee);

        foreach ($activity as $property => $value) {
            $employee->$property = $value;
        }

        return $employee;
    }

    /**
     * Undocumented function
     *
     * @param UpdateEmployeeDto $updateEmployeeDto
     * @param Employee $employee
     *
     * @return Employee
     */
    public function update(UpdateEmployeeDto $updateEmployeeDto, Employee $employee): ?Employee
    {
        DB::transaction(static function () use ($updateEmployeeDto, $employee) {
            $employee->update([
                'name' => TextHelper::titleCase($updateEmployeeDto->name),
                'cpf' => $updateEmployeeDto->cpf,
                'job' => TextHelper::titleCase($updateEmployeeDto->job),
                'gender' => $updateEmployeeDto->gender,
                'birthday' => $updateEmployeeDto->birthday,
                'birth_state_id' => $updateEmployeeDto->birthStateId,
                'birth_city' => TextHelper::titleCase($updateEmployeeDto->birthCity),
                'document_type_id' => $updateEmployeeDto->documentTypeId,
                'id_number' => $updateEmployeeDto->idNumber,
                'id_issue_date' => $updateEmployeeDto->idIssueDate,
                'id_issue_agency' => mb_strtoupper($updateEmployeeDto->idIssueAgency),
                'marital_status' => $updateEmployeeDto->maritalStatus,
                'spouse_name' => TextHelper::titleCase($updateEmployeeDto->spouseName),
                'father_name' => TextHelper::titleCase($updateEmployeeDto->fatherName),
                'mother_name' => TextHelper::titleCase($updateEmployeeDto->motherName),
                'address_street' => TextHelper::titleCase($updateEmployeeDto->addressStreet),
                'address_complement' => TextHelper::titleCase($updateEmployeeDto->addressComplement),
                'address_number' => $updateEmployeeDto->addressNumber,
                'address_district' => TextHelper::titleCase($updateEmployeeDto->addressDistrict),
                'address_postal_code' => $updateEmployeeDto->addressPostalCode,
                'address_state_id' => $updateEmployeeDto->addressStateId,
                'address_city' => TextHelper::titleCase($updateEmployeeDto->addressCity),
                'area_code' => $updateEmployeeDto->areaCode,
                'phone' => $updateEmployeeDto->phone,
                'mobile' => $updateEmployeeDto->mobile,
                'email' => mb_strtolower($updateEmployeeDto->email),
            ]);
            $employee->bankAccount()->updateOrCreate([
                'bank_name' => TextHelper::titleCase($updateEmployeeDto->bankName),
                'agency_number' => $updateEmployeeDto->agencyNumber,
                'account_number' => $updateEmployeeDto->accountNumber,
            ]);
        });

        return $employee;
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
     * @return array<string, User|Carbon|null>
     */
    private function getActivity(Employee $employee): array
    {
        $activityCreated = Activity::select('activity_log.causer_id', 'activity_log.created_at')
            ->where('activity_log.subject_id', $employee->id)
            ->where('activity_log.subject_type', Employee::class)
            ->where('activity_log.event', 'created')
            ->where('activity_log.causer_type', User::class)
            ->orderBy('activity_log.id', 'desc')
            ->first();

        $createdBy = User::find($activityCreated?->causer_id);
        $createdOn = $activityCreated?->created_at;

        $activityUpdated = Activity::select('activity_log.causer_id', 'activity_log.created_at')
            ->where('activity_log.subject_id', $employee->id)
            ->where('activity_log.subject_type', Employee::class)
            ->where('activity_log.event', 'updated')
            ->where('activity_log.causer_type', User::class)
            ->orderBy('activity_log.id', 'desc')
            ->first();

        $updatedBy = User::find($activityUpdated?->causer_id);
        $updatedOn = $activityUpdated?->created_at;

        return [
            'createdBy' => $createdBy,
            'createdOn' => $createdOn,
            'updatedBy' => $updatedBy,
            'updatedOn' => $updatedOn,
        ];
    }
}
