<?php

namespace App\Services\Dto;

use Spatie\DataTransferObject\DataTransferObject;

class StoreBondDto extends DataTransferObject
{
    public string $employeeId;

    public string $roleId;

    public string $courseId;

    public string $poleId;

    public ?string $begin;

    public ?string $end;

    public bool $volunteer;

    public ?string $knowledgeArea;

    public string $courseName;

    public string $institutionName;
}
