<?php

namespace App\Services\Dto;

use Spatie\DataTransferObject\DataTransferObject;

class UpdateEmployeeDto extends DataTransferObject
{
    public string $name;

    public string $cpf;

    public string $job;

    public string $gender;

    public string $birthday;

    public string $birth_state_id;

    public string $birth_city;

    public string $document_type_id;

    public string $id_number;

    public string $id_issue_date;

    public string $id_issue_agency;

    public string $marital_status;

    public string $spouse_name;

    public string $father_name;

    public string $mother_name;

    public string $address_street;

    public string $address_complement;

    public string $address_number;

    public string $address_district;

    public string $address_postal_code;

    public string $address_state_id;

    public string $address_city;

    public string $area_code;

    public string $phone;

    public string $mobile;

    public string $email;

    public string $bank_name;

    public string $agency_number;

    public string $account_number;
}
