<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'login' => 'required|email|exists:users,login',
            'password' => 'required',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'login.required' => 'O Login é obrigatório',
            'login.email' => 'O Login deve ser válido (Code: em)',
            'login.exists' => 'O Login deve ser válido (Code: ex)',
            'password.required' => 'A Senha é obrigatória',
        ];
    }
}
