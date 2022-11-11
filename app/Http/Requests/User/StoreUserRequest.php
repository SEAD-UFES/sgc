<?php

namespace App\Http\Requests\User;

use App\Services\Dto\StoreUserDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('user-store');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'login' => 'required|email|unique:users,login',
            'password' => 'required',
            'active' => 'sometimes',
            'employee_id' => 'nullable|exists:employees,id',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'login.required' => 'O E-mail é obrigatório',
            'login.email' => 'O endereço de E-mail deve ser válido',
            'login.unique' => 'O endereço não pode ser igual a outro já cadastrado',
            'password.required' => 'A Senha é obrigatória',
            'employee_id.exists' => 'O Colaborador deve ser válido',
        ];
    }

    public function toDto(): StoreUserDto
    {
        return new StoreUserDto(
            login: $this->validated('login') ?? '',
            password: $this->validated('password') ?? '',
            active: ($this->validated('active') ?? '') === 'on',
            employeeId: $this->validated('employee_id'),
        );
    }
}
