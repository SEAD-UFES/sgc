<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('role-store');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:50',
            'description' => 'max:110',
            'grant_value' => 'numeric',
            'grant_type_id' => 'required|exists:grant_types,id',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O Nome é obrigatório',
            'name.max' => 'O Nome deve conter no máximo 50 caracteres',
            'description.max' => 'A Descrição deve conter no máximo 50 caracteres',
            'grant_value.numeric' => 'O valor da bolsa deve ser numérico',
            'grant_type_id.required' => 'O Tipo de Bolsa é obrigatório',
            'grant_type_id.exists' => 'O Tipo de Bolsa deve estar entre os fornecidos',
        ];
    }
}
