<?php

namespace App\Services\Dto;

class UpdateCurrentPasswordDto
{
    public function __construct(
        public readonly string $password,
        public readonly string $confirmPassword,
    ) {
    }
}
