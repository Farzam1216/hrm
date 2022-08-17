<?php

namespace App\Domain\ACL\Actions;

use App\Domain\Employee\Models\Employee;

class GetEmployees
{
    /**
     * Return all active employees
     * @return Collection $employees
     */
    public function execute()
    {
        return Employee::where('status', '!=', 0)->get();
    }
}
