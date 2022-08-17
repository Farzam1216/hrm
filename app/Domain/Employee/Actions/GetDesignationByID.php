<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\Designation as Designation;

class GetDesignationByID
{
    public function execute($id)
    {
        return Designation::find($id);
    }
}