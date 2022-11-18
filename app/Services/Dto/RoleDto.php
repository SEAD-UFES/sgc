<?php

namespace App\Services\Dto;

use App\Enums\GrantTypes;

class RoleDto
{
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public readonly int $grantValue,
        public readonly GrantTypes $grantType,
    ) {
    }
}
