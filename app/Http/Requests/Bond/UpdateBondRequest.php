<?php

namespace App\Http\Requests\Bond;

use App\Enums\KnowledgeAreas;
use App\Models\Bond;
use App\Services\Dto\BondDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Gate;

class UpdateBondRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        /** @var Bond $bond */
        $bond = $this->route('bond');

        /** @var ?int $formCourseId */
        $formCourseId = $this->integer('course_id');

        return Gate::allows('bond-updateTo', ['bond' => $bond, 'course_id' => $formCourseId]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string|array<int, mixed>>
     */
    public function rules(): array
    {
        $sbr = new StoreBondRequest();
        return $sbr->rules();
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        $sbr = new StoreBondRequest();
        return $sbr->messages();
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
