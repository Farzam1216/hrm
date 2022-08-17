<?php


namespace App\Domain\Employee\Actions;

use App\Models\Country;

class GetAllCountries
{
    public function execute()
    {
        return Country::all();
    }
}
