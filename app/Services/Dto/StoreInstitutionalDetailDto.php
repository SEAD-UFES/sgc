<?php

namespace App\Services\Dto;

use Spatie\DataTransferObject\DataTransferObject;

class StoreInstitutionalDetailDto extends DataTransferObject
{
    public string $login;

    public string $email;
}
