<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Approval\Actions\CompareRequestedFieldWithFillable;
use App\Domain\Employee\Models\Employee;
use App\Domain\Employee\Models\EmployeeBankAccount;
use App\Traits\AccessibleFields;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session;

class UpdateEmployeeSalary
{
    use AccessibleFields;

    /**
     * Update Employee Of Specific ID.
     *
     * @param Request $request
     * @param $id
     *
     * @return RedirectResponse
     */
    public function execute($data, $id)
    {
        $employee = Employee::find($id);
        $employee->basic_salary = $data['basic_salary'];
        $employee->home_allowance = $data['home_allowance'];
        $employee->save();

        return $employee;
    }
}
