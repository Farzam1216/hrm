<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\Department;
use Illuminate\Support\Facades\Log;
use Session;

class CreateDepartment
{
    public function execute($request)
    {
        try {
            $department_exist = Department::where('department_name', $request->department_name)->first();
            if ($department_exist == null) {
                $department = Department::create([
                    'department_name' => $request->department_name,
                    'status' => $request->status,
                ]);
                Session::flash('success', trans('language.Department is created successfully'));
            } else {
                Session::flash('error', trans('language.Department with this name already exist'));
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Session::flash('error', trans('language.Failed to add new department.'));
        }
    }
}
