<?php

namespace App\Services\Dto;

class CourseClassDto
{
    public function __construct(
        public readonly int $courseId,
        public readonly string $code,
        public readonly string $name,
        public readonly string $cpp,
    ) {
    }
}
