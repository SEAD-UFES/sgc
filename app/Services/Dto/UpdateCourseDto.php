<?php

namespace App\Services\Dto;

use App\Enums\Degrees;
use Illuminate\Support\Facades\Date;

final class UpdateCourseDto
{
    public readonly string $name;
    public readonly string $description;
    public readonly Degrees $degree;
    public readonly ?Date $begin;
    public readonly ?Date $end;
    public readonly string $lmsUrl;
}
