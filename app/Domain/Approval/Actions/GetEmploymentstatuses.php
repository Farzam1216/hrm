<?php


namespace App\Domain\Approval\Actions;

use App\Domain\Employee\Models\EmploymentStatus;

class GetEmploymentstatuses
{
    /**
     * @return collection
     */
    public function execute()
    {
        return EmploymentStatus::all();
    }
}
