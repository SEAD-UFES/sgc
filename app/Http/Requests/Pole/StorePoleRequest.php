<?php

namespace App\Http\Requests\Pole;

use App\Services\Dto\PoleDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StorePoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('pole-store');
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
        ];
    }

    public function toDto(): PoleDto
    {
        return new PoleDto(
            name: (string) ($this->validated('name') ?? ''),
            description: (string) ($this->validated('description') ?? ''),
        );
    }
}
