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
            'cpf' => 'required|unique:employees,cpf',
            'job' => 'required|string',
            'genders' => 'between:1,2',
            'birthday' => 'required',
            'birthStates' => 'min:1',
            'birthCity' => 'required|string',
            'idNumber' => 'required',
            'idTypes' => 'min:1',
            'idIssueDate' => 'required',
            'idIssueAgency' => 'required|string',
            'maritalStatuses' => 'min:1',
            'spouseName' => 'nullable|string',
            'fatherName' => 'nullable|string',
            'motherName' => 'required|string',
            'addressStreet' => 'required|string',
            'addressComplement' => 'nullable|string',
            'addressNumber' => 'nullable|string',
            'addressDistrict' => 'nullable|string',
            'addressPostalCode' => 'required',
            'addressStates' => 'min:1',
            'addressCity' => 'required|string',
            'areaCode' => 'required',
            'phone' => 'required',
            'mobile' => 'required',
            'email' => 'required|email|unique:employees,email',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O Campo é obrigatório',
            'name.string' => 'O campo deve ser texto',
            'cpf.required' => 'O Campo é obrigatório',
            'cpf.unique' => 'O campo não pode repetir um previamente já cadastrado por outra pessoa',
            'job.required' => 'O Campo é obrigatório',
            'job.string' => 'O campo deve ser texto',
            'genders.between' => 'O campo deve ser preenchido com uma das opções fornecidas',
            'birthday.required' => 'O Campo é obrigatório',
            'birthStates.min' => 'O campo deve ser preenchido com uma das opções fornecidas',
            'birthCity.required' => 'O Campo é obrigatório',
            'birthCity.string' => 'O campo deve ser texto',
            'idNumber.required' => 'O Campo é obrigatório',
            'idTypes.min' => 'O campo deve ser preenchido com uma das opções fornecidas',
            'idIssueDate.required' => 'O Campo é obrigatório',
            'idIssueAgency.required' => 'O Campo é obrigatório',
            'idIssueAgency.string' => 'O campo deve ser texto',
            'maritalStatuses.min' => 'O campo deve ser preenchido com uma das opções fornecidas',
            'spouseName.string' => 'O campo deve ser texto',
            'fatherName.string' => 'O campo deve ser texto',
            'motherName.required' => 'O Campo é obrigatório',
            'motherName.string' => 'O campo deve ser texto',
            'addressStreet.required' => 'O Campo é obrigatório',
            'addressStreet.string' => 'O campo deve ser texto',
            'addressComplement.string' => 'O campo deve ser texto',
            'addressNumber.string' => 'O campo deve ser texto',
            'addressDistrict.string' => 'O campo deve ser texto',
            'addressPostalCode.required' => 'O Campo é obrigatório',
            'addressStates.min' => 'O campo deve ser preenchido com uma das opções fornecidas',
            'addressCity.required' => 'O Campo é obrigatório',
            'addressCity.string' => 'O campo deve ser texto',
            'areaCode.required' => 'O Campo é obrigatório',
            'phone.required' => 'O Campo é obrigatório',
            'mobile.required' => 'O Campo é obrigatório',
            'email.required' => 'O Campo é obrigatório',
            'email.email' => 'O compo deve ser preenchido com um email válido',
            'email.unique' => 'O campo não pode repetir um previamente já cadastrado por outra pessoa',
        ];
    }
}
