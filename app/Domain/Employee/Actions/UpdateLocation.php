<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\Location as Location;
use Illuminate\Support\Facades\Log;
use Session;

class UpdateLocation
{
    public function execute($id, $request)
    {
        try {
            $office_location = Location::find($id);
            $office_location->name = $request->name;
            $office_location->street_1 = $request->street_1;
            $office_location->street_2 = $request->street_2;
            $office_location->city = $request->city;
            $office_location->state = $request->state;
            $office_location->zip_code = $request->zip_code;
            $office_location->country = $request->country;
            $office_location->phone_number = $request->phone_number;

            $office_location->save();
            Session::flash('success', trans('language.Branch is updated successfully'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Session::flash('error', trans('language.Failed to Update Branch.'));
        }
    }
}
