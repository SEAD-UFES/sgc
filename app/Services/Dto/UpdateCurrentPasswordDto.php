<?php

namespace App\Services\Dto;

use Spatie\DataTransferObject\DataTransferObject;

class UpdateCurrentPasswordDto extends DataTransferObject
{
    public ?string $password;

    public ?string $confirmPassword;
}
