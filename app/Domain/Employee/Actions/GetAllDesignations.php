<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\Designation;

class GetAllDesignations
{
    public function execute()
    {
        return Designation::all();
    }
}
