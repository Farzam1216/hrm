<?php


namespace App\Domain\Approval\Actions;

use App\Domain\Employee\Models\Employee;

class GetAllEmployees
{
    /**
     * @return collection
     */
    public function execute()
    {
        return Employee::all();
    }
}
