<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
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
            'name' => 'required|max:50',
            'description' => 'max:110',
            'grantValue' => 'numeric',
            'grantTypes' => 'min:1',
        ];
    }

    public function messages()
    {
         return [
            'name.required' => 'O Nome é obrigatório',
            'name.max' => 'O Nome deve conter no máximo 50 caracteres',
            'description.max' => 'A Descrição deve conter no máximo 50 caracteres',
            'grantValue.numeric' => 'O valor da bolsa deve ser numérico',
            'grantTypes.min' => 'O Tipo de Bolsa é obrigatório',
        ];
    }
}
