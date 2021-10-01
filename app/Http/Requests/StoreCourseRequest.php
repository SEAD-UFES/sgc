<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
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
            'courseTypes' => 'required|exists:course_types,id',
            'begin' => 'nullable|date',
            'end' => 'nullable|date',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O Nome é obrigatório',
            'name.max' => 'O Nome deve conter no máximo 50 caracteres',
            'description.max' => 'A Descrição deve conter no máximo 110 caracteres',
            'courseTypes.required' => 'O Tipo é obrigatório',
            'courseTypes.exists' => 'O Tipo deve estar entre os fornecidos',
            'begin.date' => 'Início deve ser uma data',
            'end.date' => 'Início deve ser uma data',
        ];
    }
}
