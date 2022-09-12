<?php

namespace App\Http\Requests\Role;

use App\Services\Dto\UpdateRoleDto;
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
     * @return array<string, string>
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

    public function toDto(): UpdateRoleDto
    {
        return new UpdateRoleDto(
            name: $this->validated('name') ?? '',
            description: $this->validated('description') ?? '',
            grantValue: $this->validated('grant_value') ?? '',
            grantTypeId: $this->validated('grant_type_id') ?? '',
        );
    }
}
