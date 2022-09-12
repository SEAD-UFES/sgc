<?php

namespace App\Services;

use App\Events\ModelRead;
use App\Models\Employee;
use App\Models\InstitutionalDetail;
use App\Services\Dto\StoreInstitutionalDetailDto;
use App\Services\Dto\UpdateInstitutionalDetailDto;
use Illuminate\Support\Arr;

class InstitutionalDetailService
{
    /**
     * Undocumented function
     *
     * @param StoreInstitutionalDetailDto $storeInstitutionalDetailDto
     * @param Employee $employee
     *
     * @return InstitutionalDetail
     */
    public function create(StoreInstitutionalDetailDto $storeInstitutionalDetailDto, Employee $employee): InstitutionalDetail
    {
        return InstitutionalDetail::create([
            'login' => mb_strtolower($storeInstitutionalDetailDto->login),
            'email' => mb_strtolower($storeInstitutionalDetailDto->email),
            'employee_id' => $employee->id,
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
     * @param UpdateInstitutionalDetailDto $updateInstitutionalDetailDto
     * @param Employee $employee
     *
     * @return InstitutionalDetail
     */
    public function update(UpdateInstitutionalDetailDto $updateInstitutionalDetailDto, Employee $employee): InstitutionalDetail
    {
        $detail = $employee->institutionalDetail;

        if ($detail === null) {
            return $this->create(
                new StoreInstitutionalDetailDto(
                    Arr::only($updateInstitutionalDetailDto->toArray(), ['login', 'email'])
                ),
                $employee
            );
        }

        $detail->update([
            'login' => mb_strtolower($updateInstitutionalDetailDto->login),
            'email' => mb_strtolower($updateInstitutionalDetailDto->email),
        ]);

        return $detail;
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
