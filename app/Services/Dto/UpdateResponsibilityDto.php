<?php

namespace App\Services\Dto;

use Spatie\DataTransferObject\DataTransferObject;

class UpdateResponsibilityDto extends DataTransferObject
{
    public string $userId;

    public string $userTypeId;

    public ?string $courseId;

    public ?string $begin;

    public ?string $end;
}
