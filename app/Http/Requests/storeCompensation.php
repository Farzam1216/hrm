<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class storeCompensation extends FormRequest
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
            'effective_date' => 'required',
            'pay_schedule_id' => 'required',
            'pay_type' => 'required',
            'pay_rate' => 'required',
            'overtime_status' => 'required',
            'change_reason_id' => 'required',
            'comment' => 'required',
        ];
    }
}
