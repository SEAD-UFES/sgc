<?php

namespace App\Services\Dto;

final class UpdateCurrentPasswordDto
{
    public readonly ?string $password;
    public readonly ?string $confirmPassword;
}
