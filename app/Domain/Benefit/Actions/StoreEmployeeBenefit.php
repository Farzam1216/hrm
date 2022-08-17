<?php


namespace App\Domain\Benefit\Actions;

use Carbon\Carbon;

class StoreEmployeeBenefit
{
    public function execute($request)
    {
        $employeeCost = (new JsonEncodeBenefitCost())->execute($request, 'employee');
        $companyCost = (new JsonEncodeBenefitCost())->execute($request, 'company');
        //If Benefits Exist for specific group plan "Update" else "Create" new field in Table
        if ((new EmployeeBenefitExists())->execute($request)) {
            $employeeBenefit = (new UpdateEmployeeBenefit())->execute($request, $employeeCost, $companyCost);
        } else {
            $employeeBenefit = (new AddEmployeeInBenefit())->execute($request, $employeeCost, $companyCost);
        }
        //Get Benefit Plan details
        $groupPlan = (new GetEmployeeBenefitPlan)->execute($request['group_plan_ID']);
        //If current date is greater than effective date , set current status else store status for future.
        if (Carbon::parse($request['effective_date'])->startOfDay()->lte(Carbon::now()->startofDay())) {
            $trackingField = (new CreateStatusTrackingFieldJSON())->execute(Carbon::parse($request['effective_date']), $request['status'], $groupPlan['name'], 1, $request['comment']);
        } else {
            $trackingField = (new CreateStatusTrackingFieldJSON())->execute(Carbon::parse($request['effective_date']), $request['status'], $groupPlan['name'], 0, $request['comment']);
        }
        //Update status if already exists else create new field in table and delete expired status
        if ((new BenefitStatusExists())->execute($employeeBenefit['id'], $request['status'])) {
            (new UpdateBenefitStatusTransaction())->execute($employeeBenefit->id, $request, $trackingField);
            (new DeletePreviousBenefitStatus())->execute($employeeBenefit->id, $groupPlan['name']);
        } else {
            (new StoreEmployeeBenefitStatus())->execute($employeeBenefit->id, $request, $trackingField);
            (new DeletePreviousBenefitStatus())->execute($employeeBenefit->id, $groupPlan['name']);
        }
    }
}
