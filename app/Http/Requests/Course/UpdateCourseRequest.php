<?php

namespace App\Http\Requests\Course;

use App\Services\Dto\UpdateCourseDto;
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
     * @return array<string, string>
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

    public function toDto(): UpdateCourseDto
    {
        return new UpdateCourseDto(
            name: $this->validated('name') ?? '',
            description: $this->validated('description') ?? '',
            courseTypeId: $this->validated('course_type_id') ?? '',
            begin: $this->validated('begin'),
            end: $this->validated('end'),
            lmsUrl: $this->validated('lms_url') ?? '',
        );
    }
}
