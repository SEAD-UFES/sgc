<?php

namespace App\Services\Dto;

use Spatie\DataTransferObject\DataTransferObject;

class StoreResposibilityDto extends DataTransferObject
{
    public string $user_id;

    public string $user_type_id;

    public string $course_id;

    public string $begin;

    public string $end;
}
