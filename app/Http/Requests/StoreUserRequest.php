<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
            'roles' => 'min:1',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'O E-mail é obrigatório',
            'email.email' => 'O endereço de E-mail deve ser válido',
            'password.required' => 'A Senha é obrigatória',
            'roles.min' => 'A Atribuição é obrigatória',
        ];
    }
}
