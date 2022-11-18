<?php

namespace App\Http\Requests\Course;

use App\Enums\Degrees;
use App\Services\Dto\CourseDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\Enum;

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
     * @return array<string, string|array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:50',
            'description' => 'max:110',
            'degree' => ['required', new Enum(Degrees::class)],
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
            'degree.required' => 'O Tipo é obrigatório',
            'degree.Illuminate\Validation\Rules\Enum' => 'O Tipo deve estar entre os fornecidos',
            'lms_url.url' => 'O Endereço do AVA deve ser uma URL válida',
        ];
    }

    public function toDto(): CourseDto
    {
        return new CourseDto(
            name: strval($this->validated('name') ?? ''),
            description: strval($this->validated('description') ?? ''),
            degree: Degrees::from(strval($this->validated('degree'))),
            lmsUrl: strval($this->validated('lms_url') ?? ''),
        );
    }
}
