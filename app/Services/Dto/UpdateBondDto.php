<?php

namespace App\Services\Dto;

use Spatie\DataTransferObject\DataTransferObject;

class UpdateBondDto extends DataTransferObject
{
    public string $employeeId;

    public string $roleId;

    public string $courseId;

    public string $poleId;

    public string $begin;

    public string $end;

    public string $announcement;

    public bool $volunteer;

    public ?string $knowledgeArea;

    public string $courseName;

    public string $institutionName;
}
