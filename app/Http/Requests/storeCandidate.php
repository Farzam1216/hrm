<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class storeCandidate extends FormRequest
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
            'position' => 'required',
            'name' => 'required',
            'fname' => 'required',
            'city' => 'required',
            'email' => 'regex:/^.+@.+$/i',
            //'cv' => 'required|mimes:doc,docx,pdf,txt',
            'job_status' => 'required',
        ];
    }
}
