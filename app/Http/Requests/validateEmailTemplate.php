<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class validateEmailTemplate extends FormRequest
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
            'template_name' => 'required',
            'subject' => 'required',
            'message' => 'required',
        ];
    }
}
