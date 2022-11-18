<?php

namespace App\Services\Dto;

use App\Enums\Degrees;

class CourseDto
{
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public readonly Degrees $degree,
        public readonly string $lmsUrl,
    ) {
    }
}
