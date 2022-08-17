<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\Designation;

class GetAllDesignationsWithEmployee
{
    public function execute()
    {
        $designation=Designation::with('employee')->get();
        return $designation;
    }
}
