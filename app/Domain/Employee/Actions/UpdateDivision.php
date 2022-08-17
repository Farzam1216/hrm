<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\Division;
use Illuminate\Support\Facades\Log;
use Session;

class UpdateDivision
{
    public function execute($id, $request)
    {
        try {
            $division = Division::find($id);
            $division->name = $request->division_name;
            $division->status = $request->status;
            $division->save();
            Session::flash('success', trans('language.Division is updated successfully'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Session::flash('error', trans('language.Failed to update division.'));
        }
    }
}
