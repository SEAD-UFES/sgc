<?php

namespace App\Services\Dto;

final class UpdateUserDto
{
    public readonly string $login;
    public readonly string $password;
    public readonly bool $active;
    public readonly ?int $employeeId;
}
