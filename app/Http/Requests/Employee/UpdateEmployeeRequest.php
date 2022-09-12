<?php

namespace App\Http\Requests\Employee;

use App\Enums\Genders;
use App\Enums\MaritalStatuses;
use App\Models\Employee;
use App\Services\Dto\UpdateEmployeeDto;
use Illuminate\Foundation\Http\FormRequest;
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
            'cpf' => 'required|unique:employees,cpf,' . $id . ',id|digits:11',
            'job' => 'nullable|string',
            'gender' => ['nullable', new Enum(Genders::class)],
            'birthday' => 'nullable|date',
            'birth_state_id' => 'nullable|exists:states,id',
            'birth_city' => 'nullable|string',
            'id_number' => 'nullable|numeric',
            'document_type_id' => 'nullable|exists:document_types,id',
            'id_issue_date' => 'nullable|date',
            'id_issue_agency' => 'nullable|string',
            'marital_status' => ['nullable', new Enum(MaritalStatuses::class)],
            'spouse_name' => 'nullable|string',
            'father_name' => 'nullable|string',
            'mother_name' => 'nullable|string',
            'address_street' => 'nullable|string',
            'address_complement' => 'nullable|string',
            'address_number' => 'nullable|numeric',
            'address_district' => 'nullable|string',
            'address_postal_code' => 'nullable|numeric',
            'address_state_id' => 'nullable|exists:states,id',
            'address_city' => 'nullable|string',
            'area_code' => 'nullable|numeric',
            'phone' => 'nullable|numeric',
            'mobile' => 'nullable|numeric',
            'email' => 'required|email|unique:employees,email,' . $id . ',id',
            'bank_name' => 'nullable|string|required_with:agency_number,account_number',
            'agency_number' => 'nullable|string|required_with:bank_name,account_number',
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
            'cpf.required' => 'O CPF é obrigatório',
            'cpf.unique' => 'O CPF não pode repetir um previamente já cadastrado por outra pessoa',
            'cpf.digits' => 'O CPF deve ser um número de 11 dígitos',
            'job.string' => 'O campo deve ser texto',
            'gender.Illuminate\Validation\Rules\Enum' => 'O campo deve ser preenchido com uma das opções fornecidas',
            'birthday.date' => 'Nascimento deve ser uma data',
            'birth_state_id.exists' => 'O campo deve ser preenchido com uma das opções fornecidas',
            'birth_city.string' => 'O campo deve ser texto',
            'id_number.numeric' => 'O Documento deve ser um número',
            'document_type_id.exists' => 'O campo deve ser preenchido com uma das opções fornecidas',
            'id_issue_date.date' => 'Expedição deve ser uma data',
            'id_issue_agency.string' => 'O campo deve ser texto',
            'marital_status.Illuminate\Validation\Rules\Enum' => 'O campo deve ser preenchido com uma das opções fornecidas',
            'spouse_name.string' => 'O campo deve ser texto',
            'father_name.string' => 'O campo deve ser texto',
            'mother_name.string' => 'O campo deve ser texto',
            'address_street.string' => 'O campo deve ser texto',
            'address_complement.string' => 'O campo deve ser texto',
            'address_number.numeric' => 'O Número deve ser número',
            'address_district.string' => 'O campo deve ser texto',
            'address_postal_code.numeric' => 'O CEP deve ser número',
            'address_state_id.exists' => 'O campo deve ser preenchido com uma das opções fornecidas',
            'address_city.string' => 'O campo deve ser texto',
            'area_code.numeric' => 'O campo deve ser número',
            'phone.numeric' => 'O campo deve ser número',
            'mobile.numeric' => 'O campo deve ser número',
            'email.required' => 'O email é obrigatório',
            'email.email' => 'O email deve ser válido',
            'email.unique' => 'O email não pode repetir um previamente já cadastrado por outra pessoa',
        ];
    }

    public function toDto(): UpdateEmployeeDto
    {
        return new UpdateEmployeeDto(
            name: $this->validated('name') ?? '',
            cpf: $this->validated('cpf') ?? '',
            job: $this->validated('job') ?? '',
            gender: $this->validated('gender'),
            birthday: $this->validated('birthday') ?? '',
            birthStateId: $this->validated('birth_state_id'),
            birthCity: $this->validated('birth_city') ?? '',
            documentTypeId: $this->validated('document_type_id'),
            idNumber: $this->validated('id_number') ?? '',
            idIssueDate: $this->validated('id_issue_date') ?? '',
            idIssueAgency: $this->validated('id_issue_agency') ?? '',
            maritalStatus: $this->validated('marital_status'),
            spouseName: $this->validated('spouse_name') ?? '',
            fatherName: $this->validated('father_name') ?? '',
            motherName: $this->validated('mother_name') ?? '',
            addressStreet: $this->validated('address_street') ?? '',
            addressComplement: $this->validated('address_complement') ?? '',
            addressNumber: $this->validated('address_number') ?? '',
            addressDistrict: $this->validated('address_district') ?? '',
            addressPostalCode: $this->validated('address_postal_code') ?? '',
            addressStateId: $this->validated('address_state_id'),
            addressCity: $this->validated('address_city') ?? '',
            areaCode: $this->validated('area_code') ?? '',
            phone: $this->validated('phone') ?? '',
            mobile: $this->validated('mobile') ?? '',
            email: $this->validated('email') ?? '',
            bankName: $this->validated('bank_name') ?? '',
            agencyNumber: $this->validated('agency_number') ?? '',
            accountNumber: $this->validated('account_number') ?? '',
        );
    }
}
