<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\EmployeeVisa;
use App\Domain\Employee\Models\VisaType;
use App\Models\Country;
use Illuminate\Support\Facades\Log;
use Session;

class StoreEmployeeVisaDetails
{
    public function execute($request)
    {
        try {
          $data = EmployeeVisa::create([
                'visa_type_id' => $request->visa_type_id,
                'country_id' => $request->country_id,
                'issue_date' => $request->issue_date,
                'expire_date' => $request->expire_date,
                'note' => $request->note,
                'employee_id' => $request->employee_id,
            ]);
            Session::flash('success', trans('language.Employee Visa is created successfully'));
            //get country and visa type obj to show in vue
            $data->country = Country::find($data->country_id); 
            $data->visa_type = VisaType::find($data->visa_type_id); 
            return $data;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Session::flash('error', trans('language.Failed to Add Visa Details.'));
        }
    }
}
