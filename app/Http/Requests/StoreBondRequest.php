<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBondRequest extends FormRequest
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
            
            'employees' => 'min:1',
            'roles' => 'min:1',
            'courses' => 'min:1',
            'poles' => 'min:1',
        ];
    }

    public function messages()
    {
        return [
            'employees.min' => 'O colaborador deve ser preenchido com uma das opções fornecidas',
            'roles.min' => 'A atribuição deve ser preenchido com uma das opções fornecidas',
            'courses.min' => 'O curso deve ser preenchido com uma das opções fornecidas',
            'poles.min' => 'O polo deve ser preenchido com uma das opções fornecidas',
        ];
    }
}
