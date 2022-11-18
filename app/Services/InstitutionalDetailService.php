<?php

namespace App\Services;

use App\Events\ModelRead;
use App\Models\Employee;
use App\Models\InstitutionalDetail;
use App\Services\Dto\InstitutionalDetailDto;

class InstitutionalDetailService
{
    /**
     * Undocumented function
     *
     * @param InstitutionalDetailDto $storeInstitutionalDetailDto
     * @param Employee $employee
     *
     * @return InstitutionalDetail
     */
    public function create(InstitutionalDetailDto $storeInstitutionalDetailDto, Employee $employee): InstitutionalDetail
    {
        return $employee->institutionalDetail()->create([
            'login' => mb_strtolower($storeInstitutionalDetailDto->login),
            'email' => mb_strtolower($storeInstitutionalDetailDto->email),
        ]);
    }

    /**
     * Undocumented function
     *
     * @param InstitutionalDetail $institutionalDetail
     *
     * @return InstitutionalDetail
     */
    public function read(InstitutionalDetail $institutionalDetail): InstitutionalDetail
    {
        ModelRead::dispatch($institutionalDetail);

        return $institutionalDetail;
    }

    /**
     * Undocumented function
     *
     * @param InstitutionalDetailDto $updateInstitutionalDetailDto
     * @param Employee $employee
     *
     * @return InstitutionalDetail
     */
    public function update(InstitutionalDetailDto $updateInstitutionalDetailDto, Employee $employee): InstitutionalDetail
    {
        return $employee->institutionalDetail()->updateOrCreate(
            ['employee_id' => $employee->id],
            [
                'login' => mb_strtolower($updateInstitutionalDetailDto->login),
                'email' => mb_strtolower($updateInstitutionalDetailDto->email),
            ]
        );
    }

    /**
     * Undocumented function
     *
     * @param InstitutionalDetail $institutionalDetail
     *
     * @return void
     */
    public function delete(InstitutionalDetail $institutionalDetail)
    {
        $institutionalDetail->delete();
    }
}
