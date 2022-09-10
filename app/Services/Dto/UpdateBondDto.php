<?php

namespace App\Services\Dto;

use Spatie\DataTransferObject\DataTransferObject;

class UpdateBondDto extends DataTransferObject
{
    public string $employee_id;

    public string $role_id;

    public string $course_id;

    public string $pole_id;

    public string $begin;

    public string $end;

    public string $knowledge_area;

    public string $course_name;

    public string $institution_name;
}
