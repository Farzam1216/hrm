<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\Location as Location;

class GetLocationByID
{
    public function execute($id)
    {
        return Location::find($id);
    }
}
