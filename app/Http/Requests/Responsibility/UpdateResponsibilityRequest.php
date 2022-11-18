<?php

namespace App\Http\Requests\Responsibility;

use App\Services\Dto\ResponsibilityDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Gate;

class UpdateResponsibilityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('responsibility-update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        $storeResponsibilityRequest = new StoreResponsibilityRequest();
        return $storeResponsibilityRequest->rules();
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        $storeResponsibilityRequest = new StoreResponsibilityRequest();
        return $storeResponsibilityRequest->messages();
    }

    public function toDto(): ResponsibilityDto
    {
        return new ResponsibilityDto(
            userId: intval($this->validated('user_id')),
            userTypeId: intval($this->validated('user_type_id')),
            courseId: $this->validated('course_id') !== null ? intval($this->validated('course_id')) : null,
            begin: Date::parse($this->validated('begin')),
            end: $this->validated('end') !== null ? Date::parse($this->validated('end')) : null,
        );
    }
}
