<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\Designation;
use Illuminate\Support\Facades\Log;
use Session;

class UpdateDesignation
{
    public function execute($id, $request)
    {
        try {
            $designation = Designation::find($id);
            $designation->designation_name = $request->designation_name;
            $designation->status = $request->status;
            $designation->save();
            Session::flash('success', trans('language.Designation name is updated successfully'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Session::flash('error', trans('language.Failed to update designation.'));
        }
    }
}
