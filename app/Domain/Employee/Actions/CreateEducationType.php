<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\EducationType;
use Illuminate\Support\Facades\Log;
use Session;

class CreateEducationType
{
    public function execute($request)
    {
        try {
            $educationType_exist = EducationType::where('education_type', $request->education_type)->first();
            if ($educationType_exist == null) {
                $educationType = EducationType::create([
                    'education_type' => $request->education_type,
                    'status' => $request->status,
                ]);
                Session::flash('success', trans('language.Education Type is created successfully'));
            } else {
                Session::flash('error', trans('language.Education Type with this name already exist'));
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Session::flash('error', trans('language.Failed to Add Education Type.'));
        }
    }
}
