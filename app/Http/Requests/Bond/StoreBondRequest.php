<?php

namespace App\Http\Requests\Bond;

use App\Enums\KnowledgeAreas;
use App\Services\Dto\StoreBondDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
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
        $user = $this->user();
        $courseId = $this->get('course_id');

        return Gate::allows('bond-store-course_id', $courseId);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string|array<int, mixed>>
     */
    public function rules(): array
    {
        // TODO: change course_name in this context to knowledge_course_name
        return [

            'employee_id' => 'required|exists:employees,id',
            'role_id' => 'required|exists:roles,id',
            'course_id' => 'required|exists:courses,id',
            'pole_id' => 'required|exists:poles,id',
            'begin' => 'required|date',
            'end' => 'required|date',
            'announcement' => 'required|string',
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
            'begin.required' => 'Início da atuação é obrigatório',
            'end.required' => 'Fim da atuação é obrigatório',
            'begin.date' => 'Início deve ser uma data',
            'end.date' => 'Fim deve ser uma data',
            'announcement.required' => 'Edital é obrigatório',
            'knowledge_area.Illuminate\Validation\Rules\Enum' => 'O campo deve ser preenchido com uma das opções fornecidas',
        ];
    }

    public function toDto(): StoreBondDto
    {
        return new StoreBondDto(
            employeeId: $this->validated('employee_id') ?? '',
            roleId: $this->validated('role_id') ?? '',
            courseId: $this->validated('course_id') ?? '',
            poleId: $this->validated('pole_id') ?? '',
            begin: $this->validated('begin'),
            end: $this->validated('end'),
            announcement: $this->validated('announcement'),
            volunteer: ($this->validated('volunteer') ?? '') === 'on',
            knowledgeArea: $this->validated('knowledge_area'),
            courseName: $this->validated('course_name') ?? '',
            institutionName: $this->validated('institution_name') ?? '',
        );
    }
}
