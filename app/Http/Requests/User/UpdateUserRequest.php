<?php

namespace App\Http\Requests\User;

use App\Models\User;
use App\Services\Dto\UserDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('user-update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        /**
         * @var User $user
         */
        $user = $this->route()?->parameter('user');

        /**
         * @var int $id
         */
        $id = $user->id;

        return [
            'login' => 'required|email|unique:users,login,' . $id . ',id',
            'password' => 'sometimes',
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
            'login.required' => 'O Login é obrigatório',
            'login.email' => 'O Login deve ser um endereço de E-mail válido',
            'login.unique' => 'O endereço não pode ser igual a outro já cadastrado',
            'employee_id.exists' => 'O Colaborador deve ser válido',
        ];
    }

    public function toDto(): UserDto
    {
        return new UserDto(
            login: (string) ($this->validated('login') ?? ''),
            password: (string) ($this->validated('password') ?? ''),
            active: ($this->validated('active') ?? '') === 'on',
            employeeId: (int) $this->validated('employee_id'),
        );
    }
}
