<?php

namespace App\Http\Requests\Auth;

use App\Services\Dto\UpdateCurrentPasswordDto;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCurrentPasswordRequest extends FormRequest
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

    public function toDto(): UpdateCurrentPasswordDto
    {
        return new UpdateCurrentPasswordDto(
            password: strval($this->validated('password')),
            confirmPassword: strval($this->validated('confirm_password')),
        );
    }
}
