<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\VisaType;
use Illuminate\Support\Facades\Log;
use Session;

class UpdateVisaType
{
    public function execute($id, $request)
    {
        try {
            $visaType = VisaType::find($id);
            $visaType->visa_type = $request->visa_type;
            $visaType->status = $request->status;
            $visaType->save();
            Session::flash('success', trans('language.Visa Type is updated successfully'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Session::flash('error', trans('language.Failed to Update Visa Type.'));
        }
    }
}
