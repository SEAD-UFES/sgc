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
            
            'employees' => 'required|exists:employees,id',
            'roles' => 'required|exists:roles,id',
            'courses' => 'required|exists:courses,id',
            'poles' => 'required|exists:poles,id',
            'begin' => 'nullable|date',
            'end' => 'nullable|date',
            'volunteer' => 'sometimes',
        ];
    }

    public function messages()
    {
        return [
            'employees.required' => 'O Colaborador é obrigatório',
            'employees.exists' => 'O Colaborador deve estar entre os fornecidos',
            'roles.required' => 'A Função é obrigatória',
            'roles.exists' => 'A Função deve ser preenchido com uma das opções fornecidas',
            'courses.required' => 'O curso é obrigatório',
            'courses.exists' => 'O curso deve ser preenchido com uma das opções fornecidas',
            'poles.required' => 'O polo é obrigatório',
            'poles.exists' => 'O polo deve ser preenchido com uma das opções fornecidas',
            'begin.date' => 'Início deve ser uma data',
            'end.date' => 'Início deve ser uma data',
        ];
    }
}
