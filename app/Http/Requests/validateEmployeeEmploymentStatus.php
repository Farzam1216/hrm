<?php


namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class validateEmployeeEmploymentStatus extends FormRequest
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
            'employment_status' => 'required',
            'comment' => 'required',
        ];

    }
    public function messages()
    {
        return [
            'effective_date.required' => 'Effective date is required',
            'employment_status.required' => 'Please select a employment status',
            'comment.required' => 'Comment status is required',
        ];
    }
}