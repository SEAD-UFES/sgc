<?php

namespace App\Services\Dto;

use Spatie\DataTransferObject\DataTransferObject;

class StorePoleDto extends DataTransferObject
{
    public string $name;

    public string $description;
}
