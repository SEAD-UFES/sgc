<?php

namespace App\Http\Requests\BondDocument;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreBondDocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('bondDocument-store');
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
            'bond_id' => 'required|exists:bonds,id',
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
            'bond_id.required' => 'O Vínculo é obrigatório',
            'bond_id.exists' => 'O Vínculo deve estar entre os fornecidos',
        ];
    }
}