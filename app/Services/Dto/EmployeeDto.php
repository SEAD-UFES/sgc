<?php

namespace App\Services\Dto;

use App\Enums\Genders;
use App\Enums\MaritalStatuses;
use App\Enums\States;
use Illuminate\Support\Carbon;

final class EmployeeDto
{
    public function __construct(
        public readonly string $name, // employee
        public readonly string $cpf, // employee
        public readonly string $job, // personal detail
        public readonly Genders $gender, // employee
        public readonly Carbon $birthDate, // personal detail
        public readonly States $birthState, // personal detail
        public readonly string $birthCity, // personal detail
        public readonly MaritalStatuses $maritalStatus, // personal detail
        public readonly ?string $spouseName,
        public readonly string $fatherName, // personal detail
        public readonly string $motherName, // personal detail
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
        public readonly string $email, // employee
        public readonly string $bankName,
        public readonly string $agencyNumber,
        public readonly string $accountNumber,
    ) {
    }
}
