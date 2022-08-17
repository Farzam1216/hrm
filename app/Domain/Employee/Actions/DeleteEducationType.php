<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\EducationType;
use Illuminate\Support\Facades\Log;
use Session;

class DeleteEducationType
{
    public function execute($id)
    {
        try {
            $educationType = EducationType::find($id);
            $educationType->delete();
            Session::flash('success', trans('language.Education Type deleted successfully'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Session::flash('error', trans('language.Failed to Delete Education Type.'));
        }
    }
}
