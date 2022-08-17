<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\Location as Location;
use Illuminate\Support\Facades\Log;
use Session;

class DeleteLocation
{
    public function execute($id)
    {
        try {
            $locations = Location::find($id);
            $locations->delete();
            Session::flash('success', trans('Location deleted successfully'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Session::flash('error', trans('language.Failed to Delete Location.'));
        }
    }
}
