<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\Department;
use Illuminate\Support\Facades\Log;
use Session;

class DeleteDepartment
{
    public function execute($id)
    {
        try {
            $department = Department::find($id);
            $department->delete();
            Session::flash('success', trans('language.Department deleted successfully'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Session::flash('error', trans('language.Failed to delete department.'));
        }
    }
}
