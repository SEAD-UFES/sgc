<?php

namespace App\Http\Requests\Role;

use App\Enums\GrantTypes;
use App\Services\Dto\RoleDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('role-update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string|array<int, mixed>>
     */
    public function rules(): array
    {
        $srr = new StoreRoleRequest();
        return $srr->rules();
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        $srr = new StoreRoleRequest();
        return $srr->messages();
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
