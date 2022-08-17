<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\Designation;
use Illuminate\Support\Facades\Log;
use Session;

class DeleteAllDesignations
{
    public function execute()
    {
        try {
            $designation = Designation::truncate();
            Session::flash('success', trans('language.All Designation deleted successfully'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Session::flash('error', trans('language.Failed to delete designations.'));
        }
    }
}
