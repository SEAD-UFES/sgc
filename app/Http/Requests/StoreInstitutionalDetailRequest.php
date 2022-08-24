<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInstitutionalDetailRequest extends FormRequest
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
        return [
            'login' => 'nullable|string|unique:institutional_details,login',
            'email' => 'nullable|email|unique:institutional_details,email',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'login.string' => 'O Login Único deve ser texto',
            'login.unique' => 'O Login Único não pode repetir um previamente já cadastrado',
            'email.email' => 'O email deve ser válido',
            'email.unique' => 'O email não pode repetir um previamente já cadastrado',
        ];
    }
}
