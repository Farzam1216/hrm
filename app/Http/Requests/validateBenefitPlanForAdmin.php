<?php


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class validateBenefitPlanForAdmin extends FormRequest
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
            'date_range' => 'required',
            'plan_name' => 'required',
            'rate_select' => 'sometimes|required',
            'plan_coverages[][total_cost]' => 'numeric|integer',
            'reimbursement_amount' => 'numeric|integer',
            'reimbursement_frequncy' => 'sometimes|required',
//            'official_email' => 'required|email|unique:employees',
//            'personal_email' => 'email|unique:employees',
        ];
    }
}