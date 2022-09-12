<?php

namespace App\Services\Dto;

use Spatie\DataTransferObject\DataTransferObject;

class StoreApprovedDto extends DataTransferObject
{
    public string $name;

    public string $email;

    public string $areaCode;

    public string $phone;

    public string $mobile;

    public string $announcement;

    public string $roleId;

    public string $courseId;

    public string $poleId;
}
