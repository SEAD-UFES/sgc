<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCurrentPassworRequest extends FormRequest
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
            'password' => 'required',
            'confirmPassword' => 'required|same:password',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'password.required' => 'A Nova Senha é obrigatória',
            'confirmPassword.required' => 'A Confirmação da Nova Senha é obrigatória',
            'confirmPassword.same' => 'A Confirmação da Nova Senha deve ser igual a Nova Senha',
        ];
    }
}
