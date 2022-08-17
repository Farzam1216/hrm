<?php


namespace App\Domain\Benefit\Actions;

use App\Models\Country;

class GetCountriesList
{
    /**
     * @return Country[]|\Illuminate\Database\Eloquent\Collection
     */
    public function execute()
    {
        return Country::all();
    }
}
