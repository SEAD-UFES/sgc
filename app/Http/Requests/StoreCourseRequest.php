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
            'description' => 'max:50',
            'courseTypes' => 'min:1',
            /* 'begin' => 'date',
            'end' => 'date', */
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O Nome é obrigatório',
            'name.max' => 'O Nome deve conter no máximo 50 caracteres',
            'description.max' => 'A Descrição deve conter no máximo 50 caracteres',
            'courseTypes.min' => 'O Tipo é obrigatório',
            'begin.date' => 'Início deve ser uma data',
            'end.date' => 'Início deve ser uma data',
        ];
    }
}
