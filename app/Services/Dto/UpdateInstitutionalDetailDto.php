<?php

namespace App\Services\Dto;

use Spatie\DataTransferObject\DataTransferObject;

class UpdateInstitutionalDetailDto extends DataTransferObject
{
    public string $login;

    public string $email;
}
