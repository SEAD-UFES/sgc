<?php

namespace App\Http\Requests\EmployeeDocument;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreEmployeeDocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('employeeDocument-store');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'file' => 'required|mimes:pdf,jpeg,png,jpg|max:2048',
            'document_type_id' => 'required|exists:document_types,id',
            'employee_id' => 'required|exists:employees,id',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'file.required' => 'O arquivo é obrigatório',
            'file.mimes' => 'O tipo de arquivo deve ser pdf, jpg, jpeg ou png,',
            'file.max' => 'O tamanho do arquivo deve ser de no máximo 2 MB',
            'document_type_id.required' => 'O Tipo é obrigatório',
            'document_type_id.exists' => 'O Tipo deve estar entre os fornecidos',
            'employee_id.required' => 'O Colaborador é obrigatório',
            'employee_id.exists' => 'O Colaborador deve estar entre os fornecidos',
        ];
    }
}
