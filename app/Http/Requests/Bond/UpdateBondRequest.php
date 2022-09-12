<?php

namespace App\Http\Requests\Bond;

use App\Models\Bond;
use App\Services\Dto\UpdateBondDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateBondRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(Bond $bond): bool
    {
        return Gate::allows('bond-update', $bond);
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

    public function toDto(): UpdateBondDto
    {
        return new UpdateBondDto(
            employeeId: $this->validated('employee_id') ?? '',
            roleId: $this->validated('role_id') ?? '',
            courseId: $this->validated('course_id') ?? '',
            poleId: $this->validated('pole_id') ?? '',
            begin: $this->validated('begin'),
            end: $this->validated('end'),
            volunteer: ($this->validated('volunteer') ?? '') === 'on',
            knowledgeArea: $this->validated('knowledge_area'),
            courseName: $this->validated('course_name') ?? '',
            institutionName: $this->validated('institution_name') ?? '',
        );
    }
}
