<?php

namespace App\Services\Dto;

use Spatie\DataTransferObject\DataTransferObject;

class ReviewBondDto extends DataTransferObject
{
    public string $impediment;

    public string $impediment_description;
}
