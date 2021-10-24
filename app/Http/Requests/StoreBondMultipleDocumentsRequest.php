<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBondMultipleDocumentsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'files' => 'required',
            'files.*' => 'mimes:pdf,jpeg,png,jpg|max:2048',
            'bond_id' => 'required|exists:bonds,id',
        ];
    }

    public function messages()
    {
        return [
            'files.required' => 'O arquivo é obrigatório',
            'files.*.mimes' => 'O tipo de arquivo deve ser pdf, jpg, jpeg ou png,',
            'files.*.max' => 'O tamanho do arquivo deve ser de no máximo 2 MB',
            'bond_id.min' => 'O Vínculo é obrigatório',
            'bond_id.exists' => 'O Vínculo deve estar entre os fornecidos',
        ];
    }
}
