<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Employee\Models\Employee;

class GetEmployees
{
    /**
     * @return Employee[]|Collection
     */
    public function execute()
    {
        return Employee::all();
    }
}
