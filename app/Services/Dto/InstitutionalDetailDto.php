<?php

namespace App\Services\Dto;

class InstitutionalDetailDto
{
    public function __construct(
        public readonly string $login,
        public readonly string $email,
    ) {
    }
}
