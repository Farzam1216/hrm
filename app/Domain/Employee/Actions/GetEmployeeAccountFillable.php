<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\EmployeeBankAccount;

class GetEmployeeAccountFillable
{
    public function execute()
    {
        $employee = new EmployeeBankAccount();
        return $employee->getFillable();
    }
}
