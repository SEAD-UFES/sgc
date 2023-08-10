<?php

namespace App\Http\Requests\InstitutionalDetail;

use App\Models\Employee;
use App\Models\InstitutionalDetail;
use App\Services\Dto\InstitutionalDetailDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateInstitutionalDetailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('employee-update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string|array<int, mixed>>
     */
    public function rules(): array
    {
        /**
         * @var Employee $employee
         */
        $employee = $this->route('employee');

        /**
         * @var InstitutionalDetail $institutionalDetail
         */
        $institutionalDetail = $employee->institutionalDetail;

        return [
            'login' => ['required', 'string', Rule::unique('institutional_details', 'login')->ignore($institutionalDetail->employee_id, 'employee_id')],
            'email' => ['required', 'email', Rule::unique('institutional_details', 'email')->ignore($institutionalDetail->employee_id, 'employee_id')],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'login.required' => 'O Login Único é obrigatório',
            'login.string' => 'O Login Único deve ser texto',
            'login.unique' => 'O Login Único não pode repetir um previamente já cadastrado',
            'email.required' => 'O email é obrigatório',
            'email.email' => 'O email deve ser válido',
            'email.unique' => 'O email não pode repetir um previamente já cadastrado',
        ];
    }

    public function toDto(): InstitutionalDetailDto
    {
        return new InstitutionalDetailDto(
            login: (string) ($this->validated('login') ?? ''),
            email: (string) ($this->validated('email') ?? ''),
        );
    }
}
