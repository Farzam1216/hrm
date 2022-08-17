<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\Division;
use Illuminate\Support\Facades\Log;
use Session;

class DeleteDivision
{
    public function execute($id)
    {
        try {
            $division = Division::find($id);
            $division->delete();
            Session::flash('success', trans('language.Division deleted successfully.'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Session::flash('error', trans('language.Failed to delete division.'));
        }
    }
}
