<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class validateJob extends FormRequest
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
            'title' => 'required',
            'designation_id' => 'required',
            'job_status' => 'required',
            'hiring_lead_id' => 'required',
            'location_id' => 'required',
            'department_id' => 'required',
            'employment_status_id' => 'required',
        ];
    }

    /**
     * Custom message for validation.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'title.required' => 'Job title is required',
            'designation_id.required' => 'Please select a designation',
            'job_status.required' => 'Job status is required',
            'hiring_lead_id.required' => 'Please select a Hiring Lead',
            'location_id.required' => 'Please select job location',
            'department_id.required' => 'Department is not selected',
            'employment_status_id.required' => 'Please select an employment status',
        ];
    }
}
