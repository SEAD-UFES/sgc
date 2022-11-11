<?php

namespace App\Services;

use App\Events\ModelListed;
use App\Events\ModelRead;
use App\Helpers\Phone;
use App\Helpers\TextHelper;
use App\Models\Bond;
use App\Models\Course;
use App\Models\Employee;
use App\Models\User;
use App\Services\Dto\EmployeeDto;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

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

        $query = Employee::with(['identity', 'identity.type', 'personalDetail', 'address', 'phones', 'user']);
        $query = $query->AcceptRequest(Employee::$accepted_filters)->filter();
        $query = $query->sortable(['updated_at' => 'asc']);

        $employees = $query->paginate(10);
        $employees->withQueryString();

        return $employees;
    }

    /**
     * Undocumented function
     *
     * @param EmployeeDto $employeeDto
     *
     * @return mixed
     */
    public function create(EmployeeDto $employeeDto): mixed
    {
        $phoneTypeTranslation = [
            'landline' => 'Fixo',
            'mobile' => 'Celular',
            'unknown' => 'Outro',
        ];

        return DB::transaction(static function () use ($employeeDto, $phoneTypeTranslation) {
            $employee = new Employee([
                'name' => TextHelper::titleCase($employeeDto->name),
                'cpf' => $employeeDto->cpf,
                'gender' => $employeeDto->gender,
                'email' => mb_strtolower($employeeDto->email),
            ]);
            $employee->save();

            $employee->personalDetail()->create([
                'job' => TextHelper::titleCase($employeeDto->job),
                'birth_date' => $employeeDto->birthDate,
                'birth_state' => $employeeDto->birthState,
                'birth_city' => TextHelper::titleCase($employeeDto->birthCity),
                'marital_status' => $employeeDto->maritalStatus,
                'father_name' => TextHelper::titleCase($employeeDto->fatherName),
                'mother_name' => TextHelper::titleCase($employeeDto->motherName),
            ]);

            if ($employeeDto->spouseName !== '') {
                $employee->spouse()->create([
                    'name' => TextHelper::titleCase($employeeDto->spouseName),
                ]);
            }

            $employee->identity()->create([
                'type_id' => $employeeDto->documentTypeId,
                'number' => $employeeDto->identityNumber,
                'issue_date' => $employeeDto->identityIssueDate,
                'issuer' => mb_strtoupper($employeeDto->identityIssuer),
                'issuer_state' => $employeeDto->issuerState,
            ]);

            $employee->address()->create([
                'street' => TextHelper::titleCase($employeeDto->addressStreet),
                'complement' => TextHelper::titleCase($employeeDto->addressComplement),
                'number' => $employeeDto->addressNumber,
                'district' => TextHelper::titleCase($employeeDto->addressDistrict),
                'zip_code' => $employeeDto->addressZipCode,
                'state' => $employeeDto->addressState,
                'city' => TextHelper::titleCase($employeeDto->addressCity),
            ]);

            if ($employeeDto->landline !== '') {
                $landline = new Phone($employeeDto->landline);
                $employee->phones()->create([
                    'area_code' => $landline->getAreaCode(),
                    'number' => $landline->getNumber(),
                    'type' => $phoneTypeTranslation[$landline->getType()],
                ]);
            }

            $mobile = new Phone($employeeDto->mobile);
            $employee->phones()->create([
                'area_code' => $mobile->getAreaCode(),
                'number' => $mobile->getNumber(),
                'type' => $phoneTypeTranslation[$mobile->getType()],
            ]);

            $employee->bankAccount()->create([
                'bank_name' => TextHelper::titleCase($employeeDto->bankName),
                'agency_number' => $employeeDto->agencyNumber,
                'account' => $employeeDto->accountNumber,
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

        $employee->load(['identity', 'identity.type', 'personalDetail', 'address', 'phones', 'user', 'spouse']);

        $employee->setAttribute('activeBonds', $employee->bonds()->orderBy('begin', 'ASC')->get());

        $activity = $this->getActivity($employee);

        foreach ($activity as $property => $value) {
            $employee->$property = $value;
        }

        //dd($employee);

        return $employee;
    }

    /**
     * Undocumented function
     *
     * @param EmployeeDto $employeeDto
     * @param Employee $employee
     *
     * @return Employee
     */
    public function update(EmployeeDto $employeeDto, Employee $employee): ?Employee
    {
        $phoneTypeTranslation = [
            'landline' => 'Fixo',
            'mobile' => 'Celular',
            'unknown' => 'Outro',
        ];

        DB::transaction(static function () use ($employeeDto, $employee, $phoneTypeTranslation) {
            $employee->update([
                'name' => TextHelper::titleCase($employeeDto->name),
                'cpf' => $employeeDto->cpf,
                'gender' => $employeeDto->gender,
                'email' => mb_strtolower($employeeDto->email),
            ]);

            $employee->personalDetail()->updateOrCreate([
                'job' => TextHelper::titleCase($employeeDto->job),
                'birth_date' => $employeeDto->birthDate,
                'birth_state' => $employeeDto->birthState,
                'birth_city' => TextHelper::titleCase($employeeDto->birthCity),
                'marital_status' => $employeeDto->maritalStatus,
                'father_name' => TextHelper::titleCase($employeeDto->fatherName),
                'mother_name' => TextHelper::titleCase($employeeDto->motherName),
            ]);

            if ($employeeDto->spouseName !== '') {
                $employee->spouse()->updateOrCreate([
                    'name' => TextHelper::titleCase($employeeDto->spouseName),
                ]);
            }

            $employee->identity()->updateOrCreate([
                'type_id' => $employeeDto->documentTypeId,
                'number' => $employeeDto->identityNumber,
                'issue_date' => $employeeDto->identityIssueDate,
                'issuer' => mb_strtoupper($employeeDto->identityIssuer),
                'issuer_state' => $employeeDto->issuerState,
            ]);

            $employee->address()->updateOrCreate([
                'street' => TextHelper::titleCase($employeeDto->addressStreet),
                'complement' => TextHelper::titleCase($employeeDto->addressComplement),
                'number' => $employeeDto->addressNumber,
                'district' => TextHelper::titleCase($employeeDto->addressDistrict),
                'zip_code' => $employeeDto->addressZipCode,
                'state' => $employeeDto->addressState,
                'city' => TextHelper::titleCase($employeeDto->addressCity),
            ]);

            if ($employeeDto->landline !== '') {
                $employeeLandline = $employee->phones()->where('type', 'Fixo')->first();
                $newLandline = new Phone($employeeDto->landline);
                if ($employeeLandline) {
                    $employeeLandline->update([
                        'area_code' => $newLandline->getAreaCode(),
                        'number' => $newLandline->getNumber(),
                        'type' => $phoneTypeTranslation[$newLandline->getType()],
                    ]);
                } else {
                    $employee->phones()->create([
                        'area_code' => $newLandline->getAreaCode(),
                        'number' => $newLandline->getNumber(),
                        'type' => $phoneTypeTranslation[$newLandline->getType()],
                    ]);
                }
            }

            $employeeMobile = $employee->phones()->where('type', 'Celular')->first();
            $newMobile = new Phone($employeeDto->mobile);
            if ($employeeMobile) {
                $employeeMobile->update([
                    'area_code' => $newMobile->getAreaCode(),
                    'number' => $newMobile->getNumber(),
                    'type' => $phoneTypeTranslation[$newMobile->getType()],
                ]);
            } else {
                $employee->phones()->create([
                    'area_code' => $newMobile->getAreaCode(),
                    'number' => $newMobile->getNumber(),
                    'type' => $phoneTypeTranslation[$newMobile->getType()],
                ]);
            }

            $employee->bankAccount()->updateOrCreate([
                'bank_name' => TextHelper::titleCase($employeeDto->bankName),
                'agency_number' => $employeeDto->agencyNumber,
                'account' => $employeeDto->accountNumber,
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
            if (!is_null($employeeUser)) {
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
