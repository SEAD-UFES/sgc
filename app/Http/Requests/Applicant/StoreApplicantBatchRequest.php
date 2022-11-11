<?php

namespace App\Http\Requests\Applicant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreApplicantBatchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('applicant-store');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        $rules = [
            'applicants.*.check' => 'sometimes',
        ];

        if ($this->has('applicants')) {
            foreach ($this->input('applicants') as $key => $val) {
                if (isset($val['check'])) {
                    $rules['applicants.' . $key . '.name'] = 'required|string|max:255';
                    $rules['applicants.' . $key . '.email'] = 'required|email|max:255';
                    $rules['applicants.' . $key . '.area_code'] = 'required|string|max:2';
                    $rules['applicants.' . $key . '.landline'] = 'nullable|string|max:10';
                    $rules['applicants.' . $key . '.mobile'] = 'required|string|max:11';
                    $rules['applicants.' . $key . '.hiring_process'] = 'required|string|max:8';
                    $rules['applicants.' . $key . '.role_id'] = 'required|exists:roles,id';
                    $rules['applicants.' . $key . '.course_id'] = 'nullable|exists:courses,id';
                    $rules['applicants.' . $key . '.pole_id'] = 'nullable|exists:poles,id';
                }
            }
        }

        return $rules;
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'applicants.*.name.required' => 'O nome é obrigatório',
            'applicants.*.name.max' => 'O nome deve ter no máximo 255 caracteres',
            'applicants.*.email.required' => 'O e-mail é obrigatório',
            'applicants.*.email.email' => 'O e-mail deve ser válido',
            'applicants.*.email.max' => 'O e-mail deve ter no máximo 255 caracteres',
            'applicants.*.area_code.required' => 'O código de área é obrigatório',
            'applicants.*.area_code.max' => 'O código de área deve ter no máximo 2 caracteres',
            'applicants.*.phone.required' => 'O telefone é obrigatório',
            'applicants.*.phone.max' => 'O telefone deve ter no máximo 10 caracteres',
            'applicants.*.mobile.required' => 'O celular é obrigatório',
            'applicants.*.mobile.max' => 'O celular deve ter no máximo 11 caracteres',
            'applicants.*.hiring_process.required' => 'O edital é obrigatório',
            'applicants.*.hiring_process.max' => 'O edital deve ter no máximo 8 caracteres',
            'applicants.*.course_id.required' => 'O curso é obrigatório',
            'applicants.*.course_id.exists' => 'O curso deve estar entre os fornecidos',
            'applicants.*.role_id.required' => 'A função é obrigatória',
            'applicants.*.role_id.exists' => 'A função deve estar entre as fornecidas',
            'applicants.*.pole_id.required' => 'O Polo é obrigatório',
            'applicants.*.pole_id.exists' => 'O Polo deve estar entre os fornecidos',
        ];
    }
}
