<?php

namespace App\Http\Requests\Employee;

use App\Enums\Genders;
use App\Enums\MaritalStatuses;
use App\Enums\States;
use App\Models\Employee;
use App\Services\Dto\EmployeeDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\Enum;

class UpdateEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('employee-update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string|array<int, mixed>>
     */
    public function rules(): array
    {
        /**
         * @var Employee $employee
         */
        $employee = $this->route()?->parameter('employee');

        /**
         * @var int $id
         */
        $id = $employee->id;

        return [
            'name' => 'required|string',
            'cpf_number' => 'required|unique:employees,cpf,' . $id . ',id|digits:11',
            'job' => 'required|string',
            'gender' => ['required', new Enum(Genders::class)],
            'birth_date' => 'required|date',
            'birth_state' => ['required', new Enum(States::class)],
            'birth_city' => 'required|string',
            'marital_status' => ['required', new Enum(MaritalStatuses::class)],
            'spouse_name' => 'nullable|string',
            'father_name' => 'required|string',
            'mother_name' => 'required|string',
            'document_type_id' => 'required|exists:document_types,id',
            'identity_number' => 'required|numeric',
            'identity_issue_date' => 'required|date',
            'identity_issuer' => 'required|string',
            'issuer_state' => ['required', new Enum(States::class)],
            'address_street' => 'required|string',
            'address_complement' => 'required|string',
            'address_number' => 'required|numeric',
            'address_district' => 'required|string',
            'address_zip_code' => 'required|numeric',
            'address_state' => ['required', new Enum(States::class)],
            'address_city' => 'required|string',
            'landline' => 'nullable|numeric',
            'mobile' => 'required|numeric',
            'area_code' => 'required|numeric',
            'email' => 'required|email|unique:employees,email,' . $id . ',id',
            'bank_name' => 'required|string|required_with:agency_number,account',
            'agency_number' => 'required|string|required_with:bank_name,account',
            'account_number' => 'required|string|required_with:bank_name,agency_number',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório',
            'name.string' => 'O nome deve ser texto',
            'cpf_number.required' => 'O CPF é obrigatório',
            'cpf_number.unique' => 'O CPF não pode repetir um previamente já cadastrado por outra pessoa',
            'cpf_number.digits' => 'O CPF deve ser um número de 11 dígitos',
            'job.string' => 'O campo deve ser texto',
            'job.required' => 'A profissão é obrigatória',
            'gender.required' => 'O gênero é obrigatório',
            'gender.Illuminate\Validation\Rules\Enum' => 'O campo deve ser preenchido com uma das opções fornecidas',
            'birth_date.required' => 'A Data de Nasc. é obrigatória',
            'birth_date.date' => 'Nascimento deve ser uma data',
            'birth_state.required' => 'O estado de nasc. é obrigatório',
            'birth_state.Illuminate\Validation\Rules\Enum' => 'O campo deve ser preenchido com uma das opções fornecidas',
            'birth_city.required' => 'A cidade de nasc. é obrigatória',
            'birth_city.string' => 'O campo deve ser texto',
            'marital_status.required' => 'O estado civil é obrigatório',
            'marital_status.Illuminate\Validation\Rules\Enum' => 'O campo deve ser preenchido com uma das opções fornecidas',
            'spouse_name.string' => 'O campo deve ser texto',
            'father_name.required' => 'O nome do pai é obrigatório',
            'father_name.string' => 'O campo deve ser texto',
            'mother_name.required' => 'O nome da mãe é obrigatório',
            'mother_name.string' => 'O campo deve ser texto',
            'identity_number.required' => 'O número do doc. é obrigatório',
            'identity_number.numeric' => 'O Documento deve ser um número',
            'identity_issue_date.required' => 'A data de exp. é obrigatória',
            'identity_issue_date.date' => 'Expedição deve ser uma data',
            'identity_issuer.required' => 'O órgão é obrigatório',
            'identity_issuer.string' => 'O campo deve ser texto',
            'issuer_state.required' => 'O estado de exp. é obrigatório',
            'issuer_state.Illuminate\Validation\Rules\Enum' => 'O campo deve ser preenchido com uma das opções fornecidas',
            'document_type_id.required' => 'O tipo de doc. é obrigatório',
            'document_type_id.exists' => 'O campo deve ser preenchido com uma das opções fornecidas',
            'address_street.required' => 'A rua é obrigatória',
            'address_street.string' => 'O campo deve ser texto',
            'address_complement.required' => 'O complemento é obrigatório',
            'address_complement.string' => 'O campo deve ser texto',
            'address_number.required' => 'O número é obrigatório',
            'address_number.numeric' => 'O Número deve ser número',
            'address_district.required' => 'O bairro é obrigatório',
            'address_district.string' => 'O campo deve ser texto',
            'address_zip_code.required' => 'O campo CEP é obrigatório',
            'address_zip_code.numeric' => 'O CEP deve ser número',
            'address_state.required' => 'O campo Estado é obrigatório',
            'address_state.Illuminate\Validation\Rules\Enum' => 'O campo deve ser preenchido com uma das opções fornecidas',
            'address_city.required' => 'O campo Cidade é obrigatório',
            'address_city.string' => 'O campo deve ser texto',
            'landline.numeric' => 'O Telefone deve ser número',
            'mobile.required' => 'O celular é obrigatório',
            'mobile.numeric' => 'O campo deve ser número',
            'area_code.required' => 'O campo DDD é obrigatório',
            'area_code.numeric' => 'O campo deve ser número',
            'email.required' => 'O email é obrigatório',
            'email.email' => 'O email deve ser válido',
            'email.unique' => 'O email não pode repetir um previamente já cadastrado por outra pessoa',
            'bank_name.required' => 'O banco é obrigatório',
            'bank_name.string' => 'O campo deve ser texto',
            'agency_number.required' => 'A agência é obrigatória',
            'agency_number.string' => 'O campo deve ser texto',
            'account_number.required' => 'A conta é obrigatória',
            'account_number.string' => 'O campo deve ser texto',
        ];
    }

    public function toDto(): EmployeeDto
    {
        return new EmployeeDto(
            name: (string) ($this->validated('name') ?? ''),
            cpf: (string) ($this->validated('cpf_number') ?? ''),
            job: (string) ($this->validated('job') ?? ''),
            gender: Genders::from((string) $this->validated('gender')), //*** */
            birthDate: Date::parse($this->validated('birth_date')), //*** */
            birthState: States::from((string) $this->validated('birth_state')), //*** */
            birthCity: (string) ($this->validated('birth_city') ?? ''),
            maritalStatus: MaritalStatuses::from((string) $this->validated('marital_status')), //*** */
            spouseName: $this->validated('spouse_name') !== null ? (string) $this->validated('spouse_name') : null,
            fatherName: (string) ($this->validated('father_name') ?? ''),
            motherName: (string) ($this->validated('mother_name') ?? ''),
            documentTypeId: (int) $this->validated('document_type_id'),
            identityNumber: (string) ($this->validated('identity_number') ?? ''),
            identityIssueDate: Date::parse($this->validated('identity_issue_date')), //*** */
            identityIssuer: (string) ($this->validated('identity_issuer') ?? ''),
            issuerState: States::from((string) $this->validated('issuer_state')), //*** */
            addressStreet: (string) ($this->validated('address_street') ?? ''),
            addressComplement: (string) ($this->validated('address_complement') ?? ''),
            addressNumber: (string) ($this->validated('address_number') ?? ''),
            addressDistrict: (string) ($this->validated('address_district') ?? ''),
            addressZipCode: (string) ($this->validated('address_zip_code') ?? ''),
            addressState: States::from((string) $this->validated('address_state')), //*** */
            addressCity: (string) ($this->validated('address_city') ?? ''),
            landline: $this->validated('landline') !== null ? (string) $this->validated('landline') : null,
            mobile: (string) ($this->validated('mobile') ?? ''),
            areaCode: (string) ($this->validated('area_code') ?? ''),
            email: (string) ($this->validated('email') ?? ''),
            bankName: (string) ($this->validated('bank_name') ?? ''),
            agencyNumber: (string) ($this->validated('agency_number') ?? ''),
            accountNumber: (string) ($this->validated('account_number') ?? ''),
        );
    }
}
