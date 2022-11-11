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
            employeeId: intval($this->validated('employee_id')),
            roleId: intval($this->validated('role_id')),
            courseId: intval($this->validated('course_id')) !== 0 ? intval($this->validated('course_id')) : null,
            poleId: intval($this->validated('pole_id')) !== 0 ? intval($this->validated('pole_id')) : null,
            begin: Date::parse($this->validated('begin')),
            terminatedAt: $this->validated('terminated_at') !== null ? Date::parse($this->validated('terminated_at')) : null,
            hiringProcess: str($this->validated('hiring_process')),
            volunteer: ($this->validated('volunteer') ?? '') === 'on',
            qualificationKnowledgeArea: KnowledgeAreas::tryFrom($this->validated('qualification_knowledge_area')),
            qualificationCourse: str($this->validated('qualification_course')),
            qualificationInstitution: str($this->validated('qualification_institution')),
        );
    }
}
