<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\VisaType;
use Illuminate\Support\Facades\Log;
use Session;

class DeleteVisaType
{
    public function execute($id)
    {
        try {
            $visaType = VisaType::find($id);
            $visaType->delete();
            Session::flash('success', trans('language.Visa Type deleted successfully'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Session::flash('error', trans('language.Failed to Delete Visa Type.'));
        }
    }
}
