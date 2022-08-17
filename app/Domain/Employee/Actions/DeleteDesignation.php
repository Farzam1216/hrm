<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\Designation;
use Illuminate\Support\Facades\Log;
use Session;

class DeleteDesignation
{
    public function execute($id)
    {
        try {
            $designation = Designation::find($id);
            $designation->delete();
            Session::flash('success', trans('language.Designation deleted successfully'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Session::flash('error', trans('language.Failed to delete designation.'));
        }
    }
}
