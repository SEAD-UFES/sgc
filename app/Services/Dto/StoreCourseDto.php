<?php

namespace App\Services\Dto;

use Illuminate\Support\Facades\Date;

final class StoreCourseDto
{
    public readonly string $name;
    public readonly string $description;
    public readonly int $courseTypeId;
    public readonly ?Date $begin;
    public readonly ?Date $end;
    public readonly string $lmsUrl;
}
