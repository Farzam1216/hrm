<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\EmployeeVisa;

class GetEmployeeVisaInformation
{
    public function execute($id)
    {
        return EmployeeVisa::with('VisaType', 'Country')->where('employee_id', $id)->get();
    }
}
