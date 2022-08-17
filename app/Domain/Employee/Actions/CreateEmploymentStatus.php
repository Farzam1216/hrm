<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\EmploymentStatus;
use Illuminate\Support\Facades\Log;
use Session;

class CreateEmploymentStatus
{
    public function execute($request)
    {
        try {
            $employment_status_exist = EmploymentStatus::where('employment_status', $request->employment_status)->first();

            if ($employment_status_exist == null) {
                $employment_status = EmploymentStatus::create([
                    'employment_status' => $request->employment_status,
                    'description' => $request->description,
                    'status' => $request->status,
                ]);
                Session::flash('success', trans('language.Employment Status is created successfully'));
            } else {
                Session::flash('error', trans('language.Employment Status with this name already exist'));
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Session::flash('error', trans('language.Failed to Create Employment Status.'));
        }
    }
}
