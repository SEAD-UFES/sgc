<?php

namespace App\Http\Requests;

use App\Models\Employee;
use App\Models\InstitutionalDetail;
use Illuminate\Foundation\Http\FormRequest;

class UpdateInstitutionalDetailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
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

        /**
         * @var int $detailId
         */
        $detailId = $institutionalDetail->id;

        return [
            'login' => 'nullable|string|unique:institutional_details,login,' . $detailId . ',id',
            'email' => 'nullable|email|unique:institutional_details,email,' . $detailId . ',id',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'login.string' => 'O Login Único deve ser texto',
            'login.unique' => 'O Login Único não pode repetir um previamente já cadastrado',
            'email.email' => 'O email deve ser válido',
            'email.unique' => 'O email não pode repetir um previamente já cadastrado',
        ];
    }
}
