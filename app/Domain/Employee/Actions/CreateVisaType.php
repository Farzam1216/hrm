<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\VisaType;
use Illuminate\Support\Facades\Log;
use Session;

class CreateVisaType
{
    public function execute($request)
    {
        try {
            $visaType_exist = VisaType::where('visa_type', $request->visa_type)->first();
            if ($visaType_exist == null) {
                $visaType = VisaType::create([
                    'visa_type' => $request->visa_type,
                    'status' => $request->status,
                ]);
                Session::flash('success', trans('language.Visa Type is created successfully'));
            } else {
                Session::flash('error', trans('language.Visa Type with this name already exist'));
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Session::flash('error', trans('language.Failed to create Visa Type.'));
        }
    }
}
