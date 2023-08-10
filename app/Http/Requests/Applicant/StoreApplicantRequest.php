<?php

namespace App\Http\Requests\Applicant;

use App\Services\Dto\ApplicantDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreApplicantRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'area_code' => 'required|string|max:2',
            'landline' => 'nullable|string|max:10',
            'mobile' => 'required|string|max:11',
            'hiring_process' => 'required|string|max:8',
            'role_id' => 'required|exists:roles,id',
            'course_id' => 'nullable|exists:courses,id',
            'pole_id' => 'nullable|exists:poles,id',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório',
            'name.max' => 'O nome deve ter no máximo 255 caracteres',
            'email.required' => 'O e-mail é obrigatório',
            'email.email' => 'O e-mail deve ser válido',
            'email.max' => 'O e-mail deve ter no máximo 255 caracteres',
            'area_code.required' => 'O código de área é obrigatório',
            'area_code.max' => 'O código de área deve ter no máximo 2 caracteres',
            'landline.max' => 'O telefone deve ter no máximo 10 caracteres',
            'mobile.required' => 'O celular é obrigatório',
            'mobile.max' => 'O celular deve ter no máximo 11 caracteres',
            'hiring_process.required' => 'O edital é obrigatório',
            'hiring_process.max' => 'O edital deve ter no máximo 8 caracteres',
            'role_id.required' => 'A função é obrigatório',
            'role_id.exists' => 'A função deve estar entre as fornecidas',
            'course_id.exists' => 'O curso deve estar entre os fornecidos',
            'pole_id.exists' => 'O Polo deve estar entre os fornecidos',
        ];
    }

    /** @return ApplicantDto */
    public function toDto(): ApplicantDto
    {
        return new ApplicantDto(
            name: (string) ($this->validated('name') ?? ''),
            email: (string) ($this->validated('email') ?? ''),
            areaCode: (string) ($this->validated('area_code') ?? ''),
            landline: $this->validated('landline') !== null ? (string) $this->validated('landline') : null,
            mobile: (string) ($this->validated('mobile') ?? ''),
            hiringProcess: (string) ($this->validated('hiring_process') ?? ''),
            roleId: (int) $this->validated('role_id'),
            courseId: $this->validated('course_id') !== null ? (int) $this->validated('course_id') : null,
            poleId: $this->validated('pole_id') !== null ? (int) $this->validated('pole_id') : null,
        );
    }
}
