<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentStoreRequest extends FormRequest
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
            'file' => 'required|mimes:pdf,jpeg,png,jpg|max:2048'
        ];
    }

    public function messages()
    {
        return [
            'file.required' => 'O arquivo é obrigatório',
            'file.mimes' => 'O tipo de arquivo deve ser pdf, jpg, jpeg ou png,',
            'file.max' => 'O tamanho do arquivo deve ser de no máximo 2 MB',
        ];
    }
}
