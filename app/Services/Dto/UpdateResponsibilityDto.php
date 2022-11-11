<?php

namespace App\Services\Dto;

use Illuminate\Support\Facades\Date;

final class UpdateResponsibilityDto
{
    public readonly int $userId;
    public readonly int $userTypeId;
    public readonly ?int $courseId;
    public readonly ?Date $begin;
    public readonly ?Date $end;
}
