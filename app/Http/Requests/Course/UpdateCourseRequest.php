<?php

namespace App\Http\Requests\Course;

use App\Enums\Degrees;
use App\Services\Dto\CourseDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('course-update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string|array<int, mixed>>
     */
    public function rules(): array
    {
        $scr = new StoreCourseRequest();
        return $scr->rules();
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        $scr = new StoreCourseRequest();
        return $scr->messages();
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
