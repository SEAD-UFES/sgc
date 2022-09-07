<?php

namespace App\Http\Requests\Bond;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class ReviewBondRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('bond-review');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'impediment' => 'sometimes',
            'impediment_description' => 'sometimes',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
        ];
    }
}
