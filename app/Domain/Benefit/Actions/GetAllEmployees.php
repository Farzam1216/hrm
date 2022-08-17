<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Employee\Models\Employee;

class GetAllEmployees
{
    /**
     * @return Employee[]|Collection
     */

    public function execute()
    {
        return Employee::all();
    }
}
