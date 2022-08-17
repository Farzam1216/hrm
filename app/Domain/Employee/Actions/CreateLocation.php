<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\Location as Location;
use Illuminate\Support\Facades\Log;
use Session;

class CreateLocation
{
    public function execute($request)
    {
        try {
            Location::create([
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'street_1' => $request->street_1,
                'street_2' => $request->street_2,
                'city' => $request->city,
                'state' => $request->state,
                'zip_code' => $request->zip_code,
                'country' => $request->country,
            ]);
            Session::flash('success', trans('language.location is created successfully'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Session::flash('error', trans('language.Failed to add new branch location.'));
        }
    }
}
