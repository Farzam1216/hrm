<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\Designation;
use Illuminate\Support\Facades\Log;
use Session;

class CreateDesignation
{
    public function execute($request)
    {
        try {
            $designations_exist = Designation::where('designation_name', $request->designation_name)->first();
            if ($designations_exist == null) {
                Designation::create([
                    'designation_name' => $request->designation_name,
                    'status' => $request->status,
                ]);
                Session::flash('success', trans('language.Designation is created successfully'));
            } else {
                Session::flash('error', trans('language.Designation with this name already exist'));
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Session::flash('error', trans('language.Failed to add new designation.'));
        }
    }
}
