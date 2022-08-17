<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\Employee;

class GetAllEmployees
{
    public function execute()
    {
        return Employee::all();
    }
}
