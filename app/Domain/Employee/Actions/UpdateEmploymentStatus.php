<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\EmploymentStatus;
use Illuminate\Support\Facades\Log;
use Session;

class UpdateEmploymentStatus
{
    public function execute($id, $request)
    {
        try {
            $employment_status = EmploymentStatus::find($id);
            $employment_status->employment_status = $request->employment_status;
            $employment_status->description = $request->description;
            $employment_status->status = $request->status;
            $employment_status->save();
            Session::flash('success', trans('language.Employment status is updated successfully'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Session::flash('error', trans('language.Failed to Update Employment Status.'));
        }
    }
}
