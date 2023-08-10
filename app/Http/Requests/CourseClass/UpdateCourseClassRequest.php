<?php

namespace App\Http\Requests\CourseClass;

use App\Services\Dto\CourseClassDto;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseClassRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'course_id' => 'required|exists:courses,id',
            'code' => 'required|string',
            'name' => 'required|string',
            'cpp' => 'nullable|string',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'course_id.required' => 'O campo curso é obrigatório',
            'course_id.exists' => 'O curso informado não existe',
            'code.required' => 'O campo código é obrigatório',
            'code.string' => 'O campo código deve ser um texto',
            'name.required' => 'O campo nome é obrigatório',
            'name.string' => 'O campo nome deve ser um texto',
            'cpp.string' => 'O campo cpp deve ser um texto',
        ];
    }

    /** @return CourseClassDto  */
    public function toDto(): CourseClassDto
    {
        return new CourseClassDto(
            courseId: (int) $this->validated('course_id'),
            code: (string) ($this->validated('code') ?? ''),
            name: (string) ($this->validated('name') ?? ''),
            cpp: (string) ($this->validated('cpp') ?? ''),
        );
    }
}
