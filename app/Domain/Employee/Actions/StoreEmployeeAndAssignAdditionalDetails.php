<?php


namespace App\Domain\Employee\Actions;

use Session;

class StoreEmployeeAndAssignAdditionalDetails
{
    //oldName: StoreEmployee
    public function execute($request, $locale)
    {
        $employee = new CreateEmployee();
        $employee=$employee->execute($request);
        //AssignTimeOfftypes
        $typesAssigned=(new AssignTimeOffTypesToNewEmployee())->execute($employee->id);
        if ($typesAssigned) {
            Session::flash('success', trans('language.Time off Types Assgined successfully'));
        }
        (new AssignLeavesToNewEmployee())->execute($employee);
        (new AssignOnboardingTasksToNewEmployee())->execute();
        (new SendAccountDetailsToNewEmployee())->execute($employee->id, $request, $locale);
        return true;
    }
}
