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
            'gender' => ['required', new Enum(Genders::class)],
            'email' => 'required|email|unique:employees,email,' . $id . ',id',

            'job' => 'nullable|string|required_with:birth_date,birth_state,birth_city,marital_status,father_name,mother_name',
            'birth_date' => 'nullable|date|required_with:job,birth_state,birth_city,marital_status,father_name,mother_name',
            'birth_state' => ['nullable', new Enum(States::class), 'required_with:job,birth_date,birth_city,marital_status,father_name,mother_name'],
            'birth_city' => 'nullable|string|required_with:job,birth_date,birth_state,marital_status,father_name,mother_name',
            'marital_status' => ['nullable', new Enum(MaritalStatuses::class), 'required_with:job,birth_date,birth_state,birth_city,father_name,mother_name'],
            'father_name' => 'nullable|string|required_with:job,birth_date,birth_state,birth_city,marital_status,mother_name',
            'mother_name' => 'nullable|string|required_with:job,birth_date,birth_state,birth_city,marital_status,father_name',

            'spouse_name' => 'nullable|string',

            'document_type_id' => 'nullable|exists:document_types,id|required_with:identity_number,identity_issue_date,identity_issuer,issuer_state',
            'identity_number' => 'nullable|numeric|required_with:document_type_id,identity_issue_date,identity_issuer,issuer_state',
            'identity_issue_date' => 'nullable|date|required_with:document_type_id,identity_number,identity_issuer,issuer_state',
            'identity_issuer' => 'nullable|string|required_with:document_type_id,identity_number,identity_issue_date,issuer_state',
            'issuer_state' => ['nullable', new Enum(States::class), 'required_with:document_type_id,identity_number,identity_issue_date,identity_issuer'],

            'address_street' => 'nullable|string|required_with:address_complement,address_number,address_district,address_zip_code,address_state,address_city',
            'address_complement' => 'nullable|string|required_with:address_street,address_number,address_district,address_zip_code,address_state,address_city',
            'address_number' => 'nullable|numeric|required_with:address_street,address_complement,address_district,address_zip_code,address_state,address_city',
            'address_district' => 'nullable|string|required_with:address_street,address_complement,address_number,address_zip_code,address_state,address_city',
            'address_zip_code' => 'nullable|numeric|required_with:address_street,address_complement,address_number,address_district,address_state,address_city',
            'address_state' => ['nullable', new Enum(States::class), 'required_with:address_street,address_complement,address_number,address_district,address_zip_code,address_city'],
            'address_city' => 'nullable|string|required_with:address_street,address_complement,address_number,address_district,address_zip_code,address_state',

            'area_code' => 'nullable|numeric|required_with:mobile',
            'landline' => 'nullable|numeric',
            'mobile' => 'nullable|numeric|required_with:area_code',

            'bank_name' => 'nullable|string|required_with:agency_number,account',
            'agency_number' => 'nullable|string|required_with:bank_name,account',
            'account_number' => 'nullable|string|required_with:bank_name,agency_number',
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
            'gender.required' => 'O gênero é obrigatório',
            'gender.Illuminate\Validation\Rules\Enum' => 'O campo deve ser preenchido com uma das opções fornecidas',
            'email.required' => 'O email é obrigatório',
            'email.email' => 'O email deve ser válido',
            'email.unique' => 'O email não pode repetir um previamente já cadastrado por outra pessoa',

            'job.string' => 'O campo de profissão deve ser texto',
            'job.required_with' => 'O campo de profissão é obrigatório quando a data de nascimento, estado de nascimento, cidade de nascimento, estado civil, nome do pai ou nome da mãe forem preenchidos',
            'birth_date.required_with' => 'A Data de Nascimento é obrigatória quando a profissão, estado de nascimento, cidade de nascimento, estado civil, nome do pai ou nome da mãe forem preenchidos',
            'birth_date.date' => 'Nascimento deve ser uma data válida',
            'birth_state.required_with' => 'O estado de nascimento é obrigatório quando a profissão, data de nascimento, cidade de nascimento, estado civil, nome do pai ou nome da mãe forem preenchidos',
            'birth_state.Illuminate\Validation\Rules\Enum' => 'O campo de estado de nascimento deve ser preenchido com uma das opções fornecidas',
            'birth_city.required_with' => 'A cidade de nascimento é obrigatória quando a profissão, data de nascimento, estado de nascimento, estado civil, nome do pai ou nome da mãe forem preenchidos',
            'birth_city.string' => 'O campo de cidade de nascimento deve ser texto',
            'marital_status.required_with' => 'O estado civil é obrigatório quando a profissão, data de nascimento, estado de nascimento, cidade de nascimento, nome do pai ou nome da mãe forem preenchidos',
            'marital_status.Illuminate\Validation\Rules\Enum' => 'O campo de estado civil deve ser preenchido com uma das opções fornecidas',
            'father_name.required_with' => 'O nome do pai é obrigatório quando a profissão, data de nascimento, estado de nascimento, cidade de nascimento, estado civil ou nome da mãe forem preenchidos',
            'father_name.string' => 'O campo de nome do pai deve ser texto',
            'mother_name.required_with' => 'O nome da mãe é obrigatório quando a profissão, data de nascimento, estado de nascimento, cidade de nascimento, estado civil ou nome do pai forem preenchidos',
            'mother_name.string' => 'O campo de nome da mãe deve ser texto',

            'spouse_name.string' => 'O campo de nome do cônjuge deve ser texto',

            'document_type_id.required_with' => 'O tipo de documento é obrigatório quando o número do documento, data de expedição do documento, órgão emissor do documento ou estado de emissão do documento forem preenchidos',
            'document_type_id.exists' => 'O tipo de documento selecionado não é válido',
            'identity_number.required_with' => 'O número do documento é obrigatório quando o tipo de documento, data de expedição do documento, órgão emissor do documento ou estado de emissão do documento forem preenchidos',
            'identity_number.numeric' => 'O número do documento deve ser um valor numérico',
            'identity_issue_date.required_with' => 'A data de expedição do documento é obrigatória quando o tipo de documento, número do documento, órgão emissor do documento ou estado de emissão do documento forem preenchidos',
            'identity_issue_date.date' => 'A data de expedição do documento deve ser uma data válida',
            'identity_issuer.required_with' => 'O órgão emissor do documento é obrigatório quando o tipo de documento, número do documento, data de expedição do documento ou estado de emissão do documento forem preenchidos',
            'identity_issuer.string' => 'O campo de órgão emissor do documento deve ser texto',
            'issuer_state.required_with' => 'O estado de emissão do documento é obrigatório quando o tipo de documento, número do documento, data de expedição do documento ou órgão emissor do documento forem preenchidos',
            'issuer_state.Illuminate\Validation\Rules\Enum' => 'O campo de estado de emissão do documento deve ser preenchido com uma das opções fornecidas',

            'address_street.required_with' => 'O nome da rua é obrigatório quando o complemento, número do endereço, bairro, CEP, estado do endereço ou cidade do endereço forem preenchidos',
            'address_street.string' => 'O nome da rua deve ser um texto',
            'address_complement.required_with' => 'O complemento do endereço é obrigatório quando o nome da rua, número do endereço, bairro, CEP, estado do endereço ou cidade do endereço forem preenchidos',
            'address_complement.string' => 'O complemento do endereço deve ser um texto',
            'address_number.required_with' => 'O número do endereço é obrigatório quando o nome da rua, complemento, bairro, CEP, estado do endereço ou cidade do endereço forem preenchidos',
            'address_number.numeric' => 'O número do endereço deve ser um valor numérico',
            'address_district.required_with' => 'O bairro é obrigatório quando o nome da rua, complemento, número do endereço, CEP, estado do endereço ou cidade do endereço forem preenchidos',
            'address_district.string' => 'O bairro deve ser um texto',
            'address_zip_code.required_with' => 'O CEP é obrigatório quando o nome da rua, complemento, número do endereço, bairro, estado do endereço ou cidade do endereço forem preenchidos',
            'address_zip_code.numeric' => 'O CEP deve ser um valor numérico',
            'address_state.required_with' => 'O estado do endereço é obrigatório quando o nome da rua, complemento, número do endereço, bairro, CEP ou cidade do endereço forem preenchidos',
            'address_state.Illuminate\Validation\Rules\Enum' => 'O campo de estado do endereço deve ser preenchido com uma das opções fornecidas',
            'address_city.required_with' => 'A cidade do endereço é obrigatória quando o nome da rua, complemento, número do endereço, bairro, CEP ou estado do endereço forem preenchidos',
            'address_city.string' => 'A cidade do endereço deve ser um texto',

            'area_code.required_with' => 'O DDD é obrigatório quando o número de celular é preenchido',
            'area_code.numeric' => 'O DDD deve ser um valor numérico',
            'landline.numeric' => 'O telefone fixo deve ser um valor numérico',
            'mobile.required_with' => 'O celular é obrigatório quando o DDD é preenchido',
            'mobile.numeric' => 'O celular deve ser um valor numérico',

            'bank_name.required_with' => 'O nome do banco é obrigatório quando o número da agência ou número da conta forem preenchidos',
            'bank_name.string' => 'O nome do banco deve ser um texto',
            'agency_number.required_with' => 'O número da agência é obrigatório quando o nome do banco ou número da conta forem preenchidos',
            'agency_number.string' => 'O número da agência deve ser um texto',
            'account_number.required_with' => 'O número da conta é obrigatório quando o nome do banco ou número da agência forem preenchidos',
            'account_number.string' => 'O número da conta deve ser um texto',
        ];
    }

    public function toDto(): EmployeeDto
    {
        return new EmployeeDto(
            name: (string) ($this->validated('name') ?? ''),
            cpf: (string) ($this->validated('cpf_number') ?? ''),
            gender: Genders::from((string) $this->validated('gender')), //*** */
            email: (string) ($this->validated('email') ?? ''),
            job: (string) ($this->validated('job') ?? ''),
            birthDate: Date::parse($this->validated('birth_date')), //*** */
            birthState: $this->validated('birth_state') !== null ? States::from((string) $this->validated('birth_state')) : '', //*** */
            birthCity: (string) ($this->validated('birth_city') ?? ''),
            maritalStatus: $this->validated('marital_status') !== null ? MaritalStatuses::from((string) $this->validated('marital_status')) : '', //*** */
            fatherName: (string) ($this->validated('father_name') ?? ''),
            motherName: (string) ($this->validated('mother_name') ?? ''),
            spouseName: $this->validated('spouse_name') !== null ? (string) $this->validated('spouse_name') : null,
            documentTypeId: (int) $this->validated('document_type_id'),
            identityNumber: (string) ($this->validated('identity_number') ?? ''),
            identityIssueDate: Date::parse($this->validated('identity_issue_date')), //*** */
            identityIssuer: (string) ($this->validated('identity_issuer') ?? ''),
            issuerState: $this->validated('issuer_state') !== null ? States::from((string) $this->validated('issuer_state')) : '', //*** */
            addressStreet: (string) ($this->validated('address_street') ?? ''),
            addressComplement: (string) ($this->validated('address_complement') ?? ''),
            addressNumber: (string) ($this->validated('address_number') ?? ''),
            addressDistrict: (string) ($this->validated('address_district') ?? ''),
            addressZipCode: (string) ($this->validated('address_zip_code') ?? ''),
            addressState: $this->validated('address_state') !== null ? States::from((string) $this->validated('address_state')) : '', //*** */
            addressCity: (string) ($this->validated('address_city') ?? ''),
            areaCode: (string) ($this->validated('area_code') ?? ''),
            landline: $this->validated('landline') !== null ? (string) $this->validated('landline') : null,
            mobile: (string) ($this->validated('mobile') ?? ''),
            bankName: (string) ($this->validated('bank_name') ?? ''),
            agencyNumber: (string) ($this->validated('agency_number') ?? ''),
            accountNumber: (string) ($this->validated('account_number') ?? ''),
        );
    }
}
