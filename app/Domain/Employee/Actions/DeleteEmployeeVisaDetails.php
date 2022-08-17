<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\EmployeeVisa;
use Illuminate\Support\Facades\Log;
use Session;

class DeleteEmployeeVisaDetails
{
    public function execute($id)
    {
        try {
            $visa = EmployeeVisa::find($id);
            $visa->delete();
            Session::flash('success', trans('language.Employee Visa is Deleted successfully'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Session::flash('error', trans('language.Failed to Delete Visa Details.'));
        }
    }
}
