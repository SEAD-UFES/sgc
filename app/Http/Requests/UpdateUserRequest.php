<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
}
