<?php

namespace App\Http\Requests\Responsibility;

use App\Services\Dto\ResponsibilityDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Gate;

class StoreResponsibilityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('responsibility-store');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'user_type_id' => 'required|exists:user_types,id',
            'course_id' => 'nullable|exists:courses,id',
            'begin' => 'required|date',
            'end' => 'nullable|date',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
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

    public function toDto(): ResponsibilityDto
    {
        return new ResponsibilityDto(
            userId: (int) $this->validated('user_id'),
            userTypeId: (int) $this->validated('user_type_id'),
            courseId: $this->validated('course_id') !== null ? (int) $this->validated('course_id') : null,
            begin: Date::parse($this->validated('begin')),
            end: $this->validated('end') !== null ? Date::parse($this->validated('end')) : null,
        );
    }
}
