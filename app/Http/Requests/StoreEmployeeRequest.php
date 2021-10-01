<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'cpf' => 'required|unique:employees,cpf|digits:11',
            'job' => 'nullable|string',
            'genders' => 'nullable|exists:genders,id',
            'birthday' => 'nullable|date',
            'birthStates' => 'nullable|exists:states,id',
            'birthCity' => 'nullable|string',
            'idNumber' => 'nullable|numeric',
            'documentTypes' => 'nullable|exists:document_types,id',
            'idIssueDate' => 'nullable|date',
            'idIssueAgency' => 'nullable|string',
            'maritalStatuses' => 'nullable|exists:marital_statuses,id',
            'spouseName' => 'nullable|string',
            'fatherName' => 'nullable|string',
            'motherName' => 'nullable|string',
            'addressStreet' => 'nullable|string',
            'addressComplement' => 'nullable|string',
            'addressNumber' => 'nullable|numeric',
            'addressDistrict' => 'nullable|string',
            'addressPostalCode' => 'nullable|numeric',
            'addressStates' => 'nullable|exists:states,id',
            'addressCity' => 'nullable|string',
            'areaCode' => 'nullable|numeric',
            'phone' => 'nullable|numeric',
            'mobile' => 'nullable|numeric',
            'email' => 'required|email|unique:employees,email',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O nome é obrigatório',
            'name.string' => 'O nome deve ser texto',
            'cpf.required' => 'O CPF é obrigatório',
            'cpf.unique' => 'O CPF não pode repetir um previamente já cadastrado por outra pessoa',
            'cpf.digits' => 'O CPF deve ser um número de 11 dígitos',
            'job.string' => 'O campo deve ser texto',
            'genders.between' => 'O campo deve ser preenchido com uma das opções fornecidas',
            'birthday.date' => 'Nascimento deve ser uma data',
            'birthStates.exists' => 'O campo deve ser preenchido com uma das opções fornecidas',
            'birthCity.string' => 'O campo deve ser texto',
            'idNumber.numeric' => 'O Documento deve ser um número',
            'documentTypes.exists' => 'O campo deve ser preenchido com uma das opções fornecidas',
            'idIssueDate.date' => 'Expedição deve ser uma data',
            'idIssueAgency.string' => 'O campo deve ser texto',
            'maritalStatuses.exists' => 'O campo deve ser preenchido com uma das opções fornecidas',
            'spouseName.string' => 'O campo deve ser texto',
            'fatherName.string' => 'O campo deve ser texto',
            'motherName.string' => 'O campo deve ser texto',
            'addressStreet.string' => 'O campo deve ser texto',
            'addressComplement.string' => 'O campo deve ser texto',
            'addressNumber.numeric' => 'O Número deve ser número',
            'addressDistrict.string' => 'O campo deve ser texto',
            'addressPostalCode.numeric' => 'O CEP deve ser número',
            'addressStates.exists' => 'O campo deve ser preenchido com uma das opções fornecidas',
            'addressCity.string' => 'O campo deve ser texto',
            'areaCode.numeric' => 'O campo deve ser número',
            'phone.numeric' => 'O campo deve ser número',
            'mobile.numeric' => 'O campo deve ser número',
            'email.required' => 'O email é obrigatório',
            'email.email' => 'O email deve ser válido',
            'email.unique' => 'O email não pode repetir um previamente já cadastrado por outra pessoa',
        ];
    }
}
