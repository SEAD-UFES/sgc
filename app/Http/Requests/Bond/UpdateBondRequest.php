<?php

namespace App\Http\Requests\Bond;

use App\Models\Bond;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateBondRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(Bond $bond): bool
    {
        return Gate::allows('bond-update', $bond);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string|array<int, mixed>>
     */
    public function rules(): array
    {
        $sbr = new StoreBondRequest();
        return $sbr->rules();
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        $sbr = new StoreBondRequest();
        return $sbr->messages();
    }
}