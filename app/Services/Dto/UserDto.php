<?php

namespace App\Services\Dto;

class UserDto
{
    public function __construct(
        public readonly string $login,
        public readonly string $password,
        public readonly bool $active,
        public readonly ?int $employeeId,
    ) {
    }
}
