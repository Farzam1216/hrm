<?php


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class validateEmployeeJob extends FormRequest
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
            'designation_id' => 'required',
            'report_to' => 'required',
            'location_id' => 'required',
            'department_id' => 'required',
            'division_id' => 'required',
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
            'effective_date.required' => 'Effective_date is required',
            'designation_id.required' => 'Please select a designation',
            'report_to.required' => 'Report_to is required',
            'location_id.required' => 'Please select job location',
            'department_id.required' => 'Department is not selected',
            'division_id.required' => 'Division is required',
        ];
    }
}