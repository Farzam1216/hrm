<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\Location;

class GetAllLocations
{
    public function execute()
    {
        return Location::all();
    }
}
