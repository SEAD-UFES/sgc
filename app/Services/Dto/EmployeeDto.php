<?php

namespace App\Services\Dto;

use App\Enums\Genders;
use App\Enums\MaritalStatuses;
use App\Enums\States;
use Illuminate\Support\Carbon;

class EmployeeDto
{
    public function __construct(
        public readonly string $name,
        public readonly string $cpf,
        public readonly string $job,
        public readonly Genders $gender,
        public readonly Carbon $birthDate,
        public readonly States $birthState,
        public readonly string $birthCity,
        public readonly MaritalStatuses $maritalStatus,
        public readonly ?string $spouseName,
        public readonly string $fatherName,
        public readonly string $motherName,
        public readonly int $documentTypeId,
        public readonly string $identityNumber,
        public readonly Carbon $identityIssueDate,
        public readonly string $identityIssuer,
        public readonly States $issuerState,
        public readonly string $addressStreet,
        public readonly string $addressComplement,
        public readonly string $addressNumber,
        public readonly string $addressDistrict,
        public readonly string $addressZipCode,
        public readonly States $addressState,
        public readonly string $addressCity,
        public readonly ?string $landline,
        public readonly string $mobile,
        public readonly string $areaCode,
        public readonly string $email,
        public readonly string $bankName,
        public readonly string $agencyNumber,
        public readonly string $accountNumber,
    ) {
    }
}
