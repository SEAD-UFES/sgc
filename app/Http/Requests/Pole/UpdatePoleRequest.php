<?php

namespace App\Http\Requests\Pole;

use App\Services\Dto\PoleDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdatePoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('pole-update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        $spr = new StorePoleRequest();
        return $spr->rules();
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        $spr = new StorePoleRequest();
        return $spr->messages();
    }

    public function toDto(): PoleDto
    {
        return new PoleDto(
            name: (string) ($this->validated('name') ?? ''),
            description: (string) ($this->validated('description') ?? ''),
        );
    }
}
