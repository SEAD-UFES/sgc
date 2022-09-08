<?php

namespace App\Http\Requests\Responsibility;

use Illuminate\Foundation\Http\FormRequest;

class UpdateResponsibilityRequest extends FormRequest
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
}
