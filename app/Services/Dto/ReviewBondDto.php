<?php

namespace App\Services\Dto;

use Spatie\DataTransferObject\DataTransferObject;

class ReviewBondDto extends DataTransferObject
{
    public bool $impediment;

    public string $impedimentDescription;
}
