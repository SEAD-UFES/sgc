<?php

namespace App\Http\Requests\User;

use App\Models\User;
use App\Services\Dto\UpdateUserDto;
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
            'email' => 'required|email|unique:users,email,' . $id . ',id',
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
            'email.required' => 'O E-mail é obrigatório',
            'email.email' => 'O endereço de E-mail deve ser válido',
            'email.unique' => 'O endereço não pode ser igual a outro já cadastrado',
        ];
    }

    public function toDto(): UpdateUserDto
    {
        return new UpdateUserDto(
            email: $this->validated('email') ?? '',
            password: $this->validated('password') ?? '',
            active: ($this->validated('active') ?? '') === 'on',
            employeeId: $this->validated('employee_id'),
        );
    }
}
