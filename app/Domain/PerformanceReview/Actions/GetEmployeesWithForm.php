<?php


namespace App\Domain\PerformanceReview\Actions;

use App\Domain\Employee\Models\Employee;
use App\Domain\PerformanceReview\Models\PerformanceForm;

class GetEmployeesWithForm
{
    public function execute($id)
    {
        $form = PerformanceForm::find($id);
        $employees = Employee::with(['assignedForm', 'assignedForm.form'])->get();

        return $data = [
            'form' => $form,
            'employees' => $employees,
        ];
    }
}
