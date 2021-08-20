<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserTypeAssignmentRequest extends FormRequest
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
        $sUTAr = new StoreUserTypeAssignmentRequest();
        return $sUTAr->rules();
    }

    public function messages()
    {
        $sUTAr = new StoreUserTypeAssignmentRequest();
        return $sUTAr->messages();
    }
}
