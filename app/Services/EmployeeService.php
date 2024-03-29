<?php

namespace App\Services;

use App\Events\ModelListed;
use App\Events\ModelRead;
use App\Helpers\Phone;
use App\Helpers\TextHelper;
use App\Models\Employee;
use App\Models\User;
use App\Services\Dto\EmployeeDto;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

class EmployeeService
{
    /**
     * Undocumented function
     *
     * @return LengthAwarePaginator<Employee>
     */
    public function list(): LengthAwarePaginator
    {
        ModelListed::dispatch(Employee::class);

        $query = Employee::with(['identity', 'identity.type', 'personalDetail', 'address', 'phones', 'user'])

            ->AcceptRequest(Employee::$acceptedFilters)->filter()
            ->sortable()
            ->orderByDesc('employees.updated_at');

        return $query->paginate(10)
            ->withQueryString();
    }

    /**
     * Undocumented function
     *
     * @param EmployeeDto $employeeDto
     *
     * @return ?Employee
     */
    public function create(EmployeeDto $employeeDto): ?Employee
    {
        $phoneTypeTranslation = [
            'landline' => 'Fixo',
            'mobile' => 'Celular',
            'unknown' => 'Outro',
        ];

        DB::transaction(static function () use ($employeeDto, $phoneTypeTranslation) {
            $employee = new Employee([
                'name' => TextHelper::titleCase($employeeDto->name),
                'cpf' => $employeeDto->cpf,
                'gender' => $employeeDto->gender,
                'email' => mb_strtolower($employeeDto->email),
            ]);
            $employee->save();

            if ($employeeDto->job !== '' && ! empty($employeeDto->birthDate) && ! empty($employeeDto->birthState) && $employeeDto->birthCity !== '' && ! empty($employeeDto->maritalStatus) && $employeeDto->fatherName !== '' && $employeeDto->motherName !== '') {
                $employee->personalDetail()->create([
                    'job' => TextHelper::titleCase($employeeDto->job),
                    'birth_date' => $employeeDto->birthDate,
                    'birth_state' => $employeeDto->birthState,
                    'birth_city' => TextHelper::titleCase($employeeDto->birthCity),
                    'marital_status' => $employeeDto->maritalStatus,
                    'father_name' => TextHelper::titleCase($employeeDto->fatherName),
                    'mother_name' => TextHelper::titleCase($employeeDto->motherName),
                ]);
            }

            if ($employeeDto->spouseName !== null && $employeeDto->spouseName !== '') {
                $employee->spouse()->create([
                    'name' => TextHelper::titleCase($employeeDto->spouseName),
                ]);
            }

            if ($employeeDto->documentTypeId !== 0 && $employeeDto->identityNumber !== '' && ! empty($employeeDto->identityIssueDate) && $employeeDto->identityIssuer !== '' && ! empty($employeeDto->issuerState)) {
                $employee->identity()->create([
                    'type_id' => $employeeDto->documentTypeId,
                    'number' => $employeeDto->identityNumber,
                    'issue_date' => $employeeDto->identityIssueDate,
                    'issuer' => mb_strtoupper($employeeDto->identityIssuer),
                    'issuer_state' => $employeeDto->issuerState,
                ]);
            }

            if ($employeeDto->addressStreet !== '' && $employeeDto->addressComplement !== '' && $employeeDto->addressNumber !== '' && $employeeDto->addressDistrict !== '' && $employeeDto->addressZipCode !== '' && ! empty($employeeDto->addressState) && $employeeDto->addressCity !== '') {
                $employee->address()->create([
                    'street' => TextHelper::titleCase($employeeDto->addressStreet),
                    'complement' => TextHelper::titleCase($employeeDto->addressComplement),
                    'number' => $employeeDto->addressNumber,
                    'district' => TextHelper::titleCase($employeeDto->addressDistrict),
                    'zip_code' => $employeeDto->addressZipCode,
                    'state' => $employeeDto->addressState,
                    'city' => TextHelper::titleCase($employeeDto->addressCity),
                ]);
            }

            if ($employeeDto->landline !== null && ($employeeDto->landline !== '' && $employeeDto->areaCode !== '')) {
                $landline = new Phone($employeeDto->landline);
                $employee->phones()->create([
                    'area_code' => $landline->getAreaCode(),
                    'number' => $landline->getNumber(),
                    'type' => $phoneTypeTranslation[$landline->getType()],
                ]);
            }

            if ($employeeDto->mobile !== '' && $employeeDto->areaCode !== '') {
                $mobile = new Phone($employeeDto->mobile);
                $employee->phones()->create([
                    'area_code' => $mobile->getAreaCode(),
                    'number' => $mobile->getNumber(),
                    'type' => $phoneTypeTranslation[$mobile->getType()],
                ]);
            }

            if ($employeeDto->bankName !== '' && $employeeDto->agencyNumber !== '' && $employeeDto->accountNumber !== '') {
                $employee->bankAccount()->create([
                    'bank_name' => TextHelper::titleCase($employeeDto->bankName),
                    'agency_number' => $employeeDto->agencyNumber,
                    'account' => $employeeDto->accountNumber,
                ]);
            }

            return $employee;
        });

        return null;
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
    public function update(EmployeeDto $employeeDto, Employee $employee): Employee
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

            if ($employeeDto->job !== '' && ! empty($employeeDto->birthDate) && ! empty($employeeDto->birthState) && $employeeDto->birthCity !== '' && ! empty($employeeDto->maritalStatus) && $employeeDto->fatherName !== '' && $employeeDto->motherName !== '') {
                $employee->personalDetail()->updateOrCreate(
                    ['employee_id' => $employee->id],
                    [
                        'job' => TextHelper::titleCase($employeeDto->job),
                        'birth_date' => $employeeDto->birthDate,
                        'birth_state' => $employeeDto->birthState,
                        'birth_city' => TextHelper::titleCase($employeeDto->birthCity),
                        'marital_status' => $employeeDto->maritalStatus,
                        'father_name' => TextHelper::titleCase($employeeDto->fatherName),
                        'mother_name' => TextHelper::titleCase($employeeDto->motherName),
                    ]
                );
            }

            if ($employeeDto->spouseName !== null && $employeeDto->spouseName !== '') {
                $employee->spouse()->updateOrCreate(
                    ['employee_id' => $employee->id],
                    [
                        'name' => TextHelper::titleCase($employeeDto->spouseName),
                    ]
                );
            }

            if ($employeeDto->documentTypeId !== 0 && $employeeDto->identityNumber !== '' && ! empty($employeeDto->identityIssueDate) && $employeeDto->identityIssuer !== '' && ! empty($employeeDto->issuerState)) {
                $employee->identity()->updateOrCreate(
                    ['employee_id' => $employee->id],
                    [
                        'type_id' => $employeeDto->documentTypeId,
                        'number' => $employeeDto->identityNumber,
                        'issue_date' => $employeeDto->identityIssueDate,
                        'issuer' => mb_strtoupper($employeeDto->identityIssuer),
                        'issuer_state' => $employeeDto->issuerState,
                    ]
                );
            }

            if ($employeeDto->addressStreet !== '' && $employeeDto->addressComplement !== '' && $employeeDto->addressNumber !== '' && $employeeDto->addressDistrict !== '' && $employeeDto->addressZipCode !== '' && ! empty($employeeDto->addressState) && $employeeDto->addressCity !== '') {
                $employee->address()->updateOrCreate(
                    ['employee_id' => $employee->id],
                    [
                        'street' => TextHelper::titleCase($employeeDto->addressStreet),
                        'complement' => TextHelper::titleCase($employeeDto->addressComplement),
                        'number' => $employeeDto->addressNumber,
                        'district' => TextHelper::titleCase($employeeDto->addressDistrict),
                        'zip_code' => $employeeDto->addressZipCode,
                        'state' => $employeeDto->addressState,
                        'city' => TextHelper::titleCase($employeeDto->addressCity),
                    ]
                );
            }

            if ($employeeDto->landline !== null && ($employeeDto->landline !== '' && $employeeDto->areaCode !== '')) {
                $newLandline = new Phone($employeeDto->landline);
                $employee->phones()->updateOrCreate(
                    ['employee_id' => $employee->id, 'type' => 'Fixo'],
                    [
                        'area_code' => $newLandline->getAreaCode(),
                        'number' => $newLandline->getNumber(),
                        'type' => $phoneTypeTranslation[$newLandline->getType()],
                    ]
                );
            }

            if ($employeeDto->mobile !== '' && $employeeDto->areaCode !== '') {
                $newMobile = new Phone($employeeDto->mobile);
                $employee->phones()->updateOrCreate(
                    ['employee_id' => $employee->id, 'type' => 'Celular'],
                    [
                        'area_code' => $newMobile->getAreaCode(),
                        'number' => $newMobile->getNumber(),
                        'type' => $phoneTypeTranslation[$newMobile->getType()],
                    ]
                );
            }

            if ($employeeDto->bankName !== '' && $employeeDto->agencyNumber !== '' && $employeeDto->accountNumber !== '') {
                $employee->bankAccount()->updateOrCreate(
                    ['employee_id' => $employee->id],
                    [
                        'bank_name' => TextHelper::titleCase($employeeDto->bankName),
                        'agency_number' => $employeeDto->agencyNumber,
                        'account' => $employeeDto->accountNumber,
                    ]
                );
            }
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

            $employee->personalDetail?->delete();
            $employee->spouse?->delete();
            $employee->identity?->delete();
            $employee->address?->delete();
            $employee->phones->each->delete();
            $employee->bankAccount?->delete();

            $bondService = app(BondService::class);

            foreach ($employee->bonds as $bond) {
                $bondService->delete($bond);
            }

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
