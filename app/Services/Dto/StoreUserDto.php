<?php

namespace App\Services\Dto;

use Spatie\DataTransferObject\DataTransferObject;

class StoreUserDto extends DataTransferObject
{
    public string $email;

    public string $password;

    public bool $active;

    public ?string $employeeId;
}
