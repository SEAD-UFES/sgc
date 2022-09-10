<?php

namespace App\Services\Dto;

use Spatie\DataTransferObject\DataTransferObject;

class StoreRoleDto extends DataTransferObject
{
    public string $name;

    public string $description;

    public string $grant_value;

    public string $grant_type_id;
}
