<?php

namespace App\Services\Dto;

final class ApplicantDto
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $areaCode,
        public readonly ?string $landline,
        public readonly string $mobile,
        public readonly string $hiringProcess,
        public readonly int $roleId,
        public readonly ?int $courseId,
        public readonly ?int $poleId,
    ) {
    }
}
