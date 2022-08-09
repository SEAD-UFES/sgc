<?php

namespace App\Http\Requests;

use App\Enums\KnowledgeAreas;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreBondRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string|array<int, mixed>>
     */
    public function rules(): array
    {
        return [

            'employee_id' => 'required|exists:employees,id',
            'role_id' => 'required|exists:roles,id',
            'course_id' => 'required|exists:courses,id',
            'pole_id' => 'required|exists:poles,id',
            'begin' => 'nullable|date',
            'end' => 'nullable|date',
            'volunteer' => 'sometimes',
            'knowledge_area' => ['nullable', 'required_with:course_name,institution_name', new Enum(KnowledgeAreas::class)], //Rule::in(KnowledgeAreas::getValuesInAlphabeticalOrder())],
            'course_name' => 'nullable|string|required_with:knowledge_area,institution_name',
            'institution_name' => 'nullable|string|required_with:knowledge_area,course_name',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'employee_id.required' => 'O Colaborador é obrigatório',
            'employee_id.exists' => 'O Colaborador deve estar entre os fornecidos',
            'role_id.required' => 'A Função é obrigatória',
            'role_id.exists' => 'A Função deve ser preenchido com uma das opções fornecidas',
            'course_id.required' => 'O curso é obrigatório',
            'course_id.exists' => 'O curso deve ser preenchido com uma das opções fornecidas',
            'pole_id.required' => 'O polo é obrigatório',
            'pole_id.exists' => 'O polo deve ser preenchido com uma das opções fornecidas',
            'begin.date' => 'Início deve ser uma data',
            'end.date' => 'Início deve ser uma data',
        ];
    }
}
