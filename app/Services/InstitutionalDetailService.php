<?php

namespace App\Services;

use App\Events\ModelRead;
use App\Models\Employee;
use App\Models\InstitutionalDetail;
use Illuminate\Support\Arr;

class InstitutionalDetailService
{
    /**
     * Undocumented function
     *
     * @param array<string, string> $attributes
     *
     * @return InstitutionalDetail
     */
    public function create(array $attributes, Employee $employee): InstitutionalDetail
    {
        $attributes = Arr::map($attributes, static function ($value, $key) {
            return mb_strtolower($value);
        });

        $attributes = Arr::map($attributes, static function ($value, $key) {
            return $value === '' ? null : $value;
        });

        $attributes = Arr::add($attributes, 'employee_id', $employee->id);

        return InstitutionalDetail::create($attributes);
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
     * @param array<string, string> $attributes
     * @param InstitutionalDetail $institutionalDetail
     *
     * @return InstitutionalDetail
     */
    public function update(array $attributes, InstitutionalDetail $institutionalDetail, Employee $employee): InstitutionalDetail
    {
        $attributes = Arr::map($attributes, static function ($value, $key) {
            return mb_strtolower($value);
        });

        $attributes = Arr::map($attributes, static function ($value, $key) {
            return $value === '' ? null : $value;
        });

        $attributes = Arr::add($attributes, 'employee_id', $employee->id);

        $institutionalDetail->update($attributes);

        return $institutionalDetail;
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
