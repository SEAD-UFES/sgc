<?php

namespace App\Services\Dto;

use Spatie\DataTransferObject\DataTransferObject;

class StoreApprovedDto extends DataTransferObject
{
    public string $name;

    public string $email;

    public string $area_code;

    public string $phone;

    public string $mobile;

    public string $announcement;

    public string $role_id;

    public string $course_id;

    public string $pole_id;
}
