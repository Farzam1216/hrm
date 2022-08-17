<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\Division;
use App\Domain\Employee\Models\Department;
use Illuminate\Support\Facades\Log;
use Session;

class CreateDivision
{
    public function execute($request)
    {
        try {
            $requestedData = [
               'name' => $request->division_name,
                'status' => $request->status,
            ];
            $division_exist = Division::where('name', $request->division_name)->first();
            if ($division_exist  == null) {
                $team = Division::create($requestedData);
                Session::flash('success', trans('language.Division is created successfully'));
            } else {
                Session::flash('error', trans('language.Division with this name already exist'));
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Session::flash('error', trans('language.Failed to add new division.'));
        }
    }
}
