<?php

namespace App\Services\Dto;

use Spatie\DataTransferObject\DataTransferObject;

class UpdatePoleDto extends DataTransferObject
{
    public string $name;

    public string $description;
}
