<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\Division as Division;

class GetDivisionByID
{
    public function execute($id)
    {
        return Division::find($id);
    }
}