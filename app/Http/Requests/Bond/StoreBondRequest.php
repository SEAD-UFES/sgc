<?php

namespace App\Http\Requests\Bond;

use App\Enums\KnowledgeAreas;
use App\Services\Dto\BondDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Date;
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
        /** @var ?int $formCourseId */
        $formCourseId = $this->integer('course_id');

        return Gate::allows('bond-store-course_id', ['course_id' => $formCourseId]);
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
            'course_id' => 'nullable|exists:courses,id',
            'course_class_id' => 'nullable|exists:course_classes,id',
            'pole_id' => 'nullable|exists:poles,id',
            'begin' => 'required|date',
            'terminated_at' => 'nullable|date',
            'hiring_process' => 'required|string',
            'volunteer' => 'sometimes',
            'qualification_knowledge_area' => ['required', new Enum(KnowledgeAreas::class)],
            'qualification_course' => 'required|string',
            'qualification_institution' => 'required|string',
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
            'course_id.exists' => 'O curso deve ser preenchido com uma das opções fornecidas',
            'course_class_id.exists' => 'A turma deve ser preenchido com uma das opções fornecidas',
            'pole_id.exists' => 'O polo deve ser preenchido com uma das opções fornecidas',
            'begin.required' => 'Início da atuação é obrigatório',
            'begin.date' => 'Início deve ser uma data',
            'terminated_at.date' => 'Fim deve ser uma data',
            'hiring_process.required' => 'Edital é obrigatório',
            'qualification_knowledge_area.required' => 'Área de conhecimento é obrigatória',
            'qualification_knowledge_area.Illuminate\Validation\Rules\Enum' => 'O campo deve ser preenchido com uma das opções fornecidas',
            'qualification_course.required' => 'Nome do curso é obrigatório',
            'qualification_institution.required' => 'Nome da instituição é obrigatório',
        ];
    }

    public function toDto(): BondDto
    {
        return new BondDto(
            employeeId: (int) $this->validated('employee_id'),
            roleId: (int) $this->validated('role_id'),
            courseId: $this->validated('course_id') !== null ? (int) $this->validated('course_id') : null,
            courseClassId: $this->validated('course_class_id') !== null ? (int) $this->validated('course_class_id') : null,
            poleId: $this->validated('pole_id') !== null ? (int) $this->validated('pole_id') : null,
            begin: Date::parse($this->validated('begin')),
            terminatedAt: $this->validated('terminated_at') !== null ? Date::parse($this->validated('terminated_at')) : null,
            hiringProcess: (string) $this->validated('hiring_process'),
            volunteer: ($this->validated('volunteer') ?? '') === 'on',
            qualificationKnowledgeArea: KnowledgeAreas::from((string) $this->validated('qualification_knowledge_area')),
            qualificationCourse: (string) $this->validated('qualification_course'),
            qualificationInstitution: (string) $this->validated('qualification_institution'),
        );
    }
}
