<?php

namespace App\Http\Requests\Approved;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreApprovedBatchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('approved-store');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'approveds.*.check' => 'sometimes',
            'approveds.*.name' => 'required|string|max:255',
            'approveds.*.email' => 'required|email|max:255',
            'approveds.*.area_code' => 'string|max:2',   //Relaxed form
            'approveds.*.phone' => 'string|max:10',      //Relaxed form
            'approveds.*.mobile' => 'required|string|max:11',
            'approveds.*.announcement' => 'required|string|max:8',
            'approveds.*.course_id' => 'required|exists:courses,id',
            'approveds.*.role_id' => 'required|exists:roles,id',
            'approveds.*.pole_id' => 'required|exists:poles,id',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'approveds.*.name.required' => 'O nome é obrigatório',
            'approveds.*.name.max' => 'O nome deve ter no máximo 255 caracteres',
            'approveds.*.email.required' => 'O e-mail é obrigatório',
            'approveds.*.email.email' => 'O e-mail deve ser válido',
            'approveds.*.email.max' => 'O e-mail deve ter no máximo 255 caracteres',
            'approveds.*.area_code.required' => 'O código de área é obrigatório',
            'approveds.*.area_code.max' => 'O código de área deve ter no máximo 2 caracteres',
            'approveds.*.phone.required' => 'O telefone é obrigatório',
            'approveds.*.phone.max' => 'O telefone deve ter no máximo 10 caracteres',
            'approveds.*.mobile.required' => 'O celular é obrigatório',
            'approveds.*.mobile.max' => 'O celular deve ter no máximo 11 caracteres',
            'approveds.*.announcement.required' => 'O edital é obrigatório',
            'approveds.*.announcement.max' => 'O edital deve ter no máximo 8 caracteres',
            'approveds.*.course_id.required' => 'O curso é obrigatório',
            'approveds.*.course_id.exists' => 'O curso deve estar entre os fornecidos',
            'approveds.*.role_id.required' => 'A função é obrigatório',
            'approveds.*.role_id.exists' => 'A função deve estar entre as fornecidas',
            'approveds.*.pole_id.required' => 'O Polo é obrigatório',
            'approveds.*.pole_id.exists' => 'O Polo deve estar entre os fornecidos',
        ];
    }
}
