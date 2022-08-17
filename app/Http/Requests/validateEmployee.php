<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class validateEmployee extends FormRequest
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
            'picture' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'employee_no' => 'required|unique:employees',
            'firstname' => 'required',
            'lastname' => 'required',
            'official_email' => 'required|email|unique:employees',
//            'basic_salary' => 'required',
//            'home_allowance' => 'required',
        ];
    }
}
