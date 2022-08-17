<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\EmploymentStatus;
use Illuminate\Support\Facades\Log;
use Session;

class DeleteEmploymentStatus
{
    public function execute($id)
    {
        try {
            $employment_status = EmploymentStatus::find($id);
            $employment_status->delete();
            Session::flash('success', trans('language.Employment Status deleted successfully'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Session::flash('error', trans('language.Failed to Delete Employment Status.'));
        }
    }
}
