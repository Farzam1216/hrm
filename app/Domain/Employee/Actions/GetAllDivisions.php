<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\Division;

class GetAllDivisions
{
    public function execute()
    {
        return Division::all();
    }
}
