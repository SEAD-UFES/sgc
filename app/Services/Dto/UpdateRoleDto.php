<?php

namespace App\Services\Dto;

use Spatie\DataTransferObject\DataTransferObject;

class UpdateRoleDto extends DataTransferObject
{
    public string $name;

    public string $description;

    public string $grant_value;

    public string $grant_type_id;
}
