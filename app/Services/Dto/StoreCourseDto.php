<?php

namespace App\Services\Dto;

use Spatie\DataTransferObject\DataTransferObject;

class StoreCourseDto extends DataTransferObject
{
    public string $name;

    public string $description;

    public string $courseTypeId;

    public ?string $begin;

    public ?string $end;

    public string $lmsUrl;
}
