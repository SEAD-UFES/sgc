<?php

namespace App\Services\Dto;

use Spatie\DataTransferObject\DataTransferObject;

class StoreCourseDto extends DataTransferObject
{
    public string $name;

    public string $description;

    public string $course_type_id;

    public string $begin;

    public string $end;

    public string $lms_url;
}
