<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserTypeAssignmentRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'user_type_id' => 'required|exists:user_types,id',
            'course_id' => 'nullable|exists:courses,id',
            'begin' => 'required|date',
            'end' => 'nullable|date',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'O campo usuário é requerido.',
            'user_id.exists' => 'O usuário não existe na base de dados.',
            'user_type_id.required' => 'O campo tipo de usuário é requerido.',
            'user_type_id.exists' => 'O papel não existe na base de dados.',
            'course_id.exists' => 'O curso não existe na base de dados',
            'begin.required' => 'O campo Início é requerido.',
            'begin.date' => 'O início deve ser uma data.',
            'end.date' => 'O fim deve ser uma data.',
        ];
    }
}
