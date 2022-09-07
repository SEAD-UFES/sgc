<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('course-store');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:50',
            'description' => 'max:110',
            'course_type_id' => 'required|exists:course_types,id',
            'begin' => 'nullable|date',
            'end' => 'nullable|date',
            'lms_url' => 'nullable|url',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O Nome é obrigatório',
            'name.max' => 'O Nome deve conter no máximo 50 caracteres',
            'description.max' => 'A Descrição deve conter no máximo 110 caracteres',
            'course_type_id.required' => 'O Tipo é obrigatório',
            'course_type_id.exists' => 'O Tipo deve estar entre os fornecidos',
            'begin.date' => 'Início deve ser uma data',
            'end.date' => 'Início deve ser uma data',
            'lms_url.url' => 'O Endereço do AVA deve ser uma URL válida',
        ];
    }
}
