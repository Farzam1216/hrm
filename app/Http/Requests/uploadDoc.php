<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class uploadDoc extends FormRequest
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
            'document' => 'required|mimes:doc,docx,pdf|max:2000',
            'document_name' => 'required',
           
        ];
    }
}
