<?php

namespace App\Services\Dto;

use Spatie\DataTransferObject\DataTransferObject;

class StoreEmployeeDto extends DataTransferObject
{
    public string $name;

    public string $cpf;

    public string $job;

    public ?string $gender;

    public string $birthday;

    public ?string $birthStateId;

    public string $birthCity;

    public ?string $documentTypeId;

    public string $idNumber;

    public string $idIssueDate;

    public string $idIssueAgency;

    public ?string $maritalStatus;

    public string $spouseName;

    public string $fatherName;

    public string $motherName;

    public string $addressStreet;

    public string $addressComplement;

    public string $addressNumber;

    public string $addressDistrict;

    public string $addressPostalCode;

    public ?string $addressStateId;

    public string $addressCity;

    public string $areaCode;

    public string $phone;

    public string $mobile;

    public string $email;

    public string $bankName;

    public string $agencyNumber;

    public string $accountNumber;
}
