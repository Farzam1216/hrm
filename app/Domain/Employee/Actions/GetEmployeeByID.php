<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\Employee;

class GetEmployeeByID
{
    public function execute($id)
    {
        return Employee::where('id', $id)->first();
    }
}
