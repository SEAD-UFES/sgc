<?php

namespace App\Services\Dto;

class PoleDto
{
    public function __construct(
        public readonly string $name,
        public readonly string $description,
    ) {
    }
}
