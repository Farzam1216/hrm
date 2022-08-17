<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\EducationType;
use Illuminate\Support\Facades\Log;
use Session;

class UpdateEducationType
{
    public function execute($id, $request)
    {
        try {
            $educationType = EducationType::find($id);
            $educationType->education_type = $request->education_type;
            $educationType->status = $request->status;
            $educationType->save();
            Session::flash('success', trans('language.Education Type is updated successfully'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Session::flash('error', trans('language.Failed to Update Education Type.'));
        }
    }
}
