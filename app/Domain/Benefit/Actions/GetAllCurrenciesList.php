<?php


namespace App\Domain\Benefit\Actions;

use Illuminate\Support\Facades\DB;

class GetAllCurrenciesList
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function execute()
    {
        return DB::Table('currencies')->get();
    }
}
