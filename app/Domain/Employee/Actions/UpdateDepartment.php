<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\Department;
use Illuminate\Support\Facades\Log;
use Session;

class UpdateDepartment
{
    public function execute($id, $request)
    {
        try {
            $department = Department::find($id);
            $department->department_name = $request->department_name;
            $department->status = $request->status;
            $department->save();
            Session::flash('success', trans('language.Department is updated successfully'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Session::flash('error', trans('language.Failed to update department.'));
        }
    }
}
