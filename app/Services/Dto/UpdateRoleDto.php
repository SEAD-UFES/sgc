<?php

namespace App\Services\Dto;

use App\Enums\GrantTypes;

final class UpdateRoleDto
{
    public readonly string $name;
    public readonly string $description;
    public readonly int $grantValue;
    public readonly GrantTypes $grantType;
}
