<?php

namespace App\Http\Requests\Role;

use App\Enums\GrantTypes;
use App\Services\Dto\RoleDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\Enum;

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
     * @return array<string, string|array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:50',
            'description' => 'max:110',
            'grant_value' => 'numeric',
            'grant_type' => ['required', new Enum(GrantTypes::class)],
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
            'grant_type.required' => 'O Tipo de Bolsa é obrigatório',
            'grant_type.Illuminate\Validation\Rules\Enum' => 'O Tipo de Bolsa deve estar entre os fornecidos',
        ];
    }

    public function toDto(): RoleDto
    {
        return new RoleDto(
            name: (string) ($this->validated('name') ?? ''),
            description: (string) ($this->validated('description') ?? ''),
            grantValue: (int) $this->validated('grant_value'),
            grantType: GrantTypes::from((string) $this->validated('grant_type')),
        );
    }
}
