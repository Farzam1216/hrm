<?php


namespace App\Domain\Employee\Actions;

use Session;
use App\Models\Country;
use Illuminate\Support\Facades\Log;
use App\Domain\Employee\Models\VisaType;
use App\Domain\Employee\Models\EmployeeVisa;

class UpdateEmployeeVisaDetails
{
    public function execute($id, $request)
    {
        try {
            $visa = EmployeeVisa::find($id);
            $visa->visa_type_id = $request->visa_type_id;
            $visa->country_id = $request->country_id;
            $visa->issue_date = $request->issue_date;
            $visa->expire_date = $request->expire_date;
            $visa->note = $request->note;
            $visa->employee_id = $request->employee_id;
            $visa->save();
            Session::flash('success', trans('language.Employee Visa is Updated successfully'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Session::flash('error', trans('language.Failed to Update Visa Details.'));
        }
    }
}
