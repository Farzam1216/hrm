<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\EmploymentStatus;

class GetAllEmploymentStatus
{
    public function execute()
    {
        return EmploymentStatus::with('employees')->get();
    }
}
