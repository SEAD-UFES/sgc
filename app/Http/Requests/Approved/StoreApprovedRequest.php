<?php

namespace App\Http\Requests\Approved;

use App\Services\Dto\StoreApprovedDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreApprovedRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'area_code' => 'string|max:2',   //Relaxed form
            'phone' => 'string|max:10',      //Relaxed form
            'mobile' => 'required|string|max:11',
            'announcement' => 'required|string|max:8',
            'role_id' => 'required|exists:roles,id',
            'course_id' => 'required|exists:courses,id',
            'pole_id' => 'required|exists:poles,id',
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
            'phone.required' => 'O telefone é obrigatório',
            'phone.max' => 'O telefone deve ter no máximo 10 caracteres',
            'mobile.required' => 'O celular é obrigatório',
            'mobile.max' => 'O celular deve ter no máximo 11 caracteres',
            'announcement.required' => 'O edital é obrigatório',
            'announcement.max' => 'O edital deve ter no máximo 8 caracteres',
            'role_id.required' => 'A função é obrigatório',
            'role_id.exists' => 'A função deve estar entre as fornecidas',
            'course_id.required' => 'O curso é obrigatório',
            'course_id.exists' => 'O curso deve estar entre os fornecidos',
            'pole_id.required' => 'O Polo é obrigatório',
            'pole_id.exists' => 'O Polo deve estar entre os fornecidos',
        ];
    }

    public function toDto(): StoreApprovedDto
    {
        return new StoreApprovedDto(
            name: $this->validated('name') ?? '',
            email: $this->validated('email') ?? '',
            areaCode: $this->validated('area_code') ?? '',
            phone: $this->validated('phone') ?? '',
            mobile: $this->validated('mobile') ?? '',
            announcement: $this->validated('announcement') ?? '',
            roleId: $this->validated('role_id') ?? '',
            courseId: $this->validated('course_id') ?? '',
            poleId: $this->validated('pole_id') ?? '',
        );
    }
}
