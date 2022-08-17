<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Employee\Models\Employee;
use Carbon\Carbon;

class CalculateEmployeeBenefitEligibility
{
    /**
     * @param $employeeID
     * @param $groupPlan
     * @return Carbon|string
     */

    public function execute($employeeID, $groupPlan)
    {
        $hiringDate = Employee::where('id', $employeeID)->pluck('joining_date')->first();
        if ($groupPlan->eligibility == "manual") {
            $eligibilityDate = null;
        } elseif ($groupPlan->eligibility == "hire_date") {
            $hiringDate = Carbon::parse($hiringDate);
            $eligibilityDate = $hiringDate->copy();
        } elseif ($groupPlan->eligibility == "waiting_period") {
            $eligibilityDate = Carbon::parse($hiringDate . $groupPlan->wait_period . $groupPlan->type_of_period);
        } elseif ($groupPlan->eligibility == "month_after_waiting_period") {
            $eligibilityDate = Carbon::parse($hiringDate . $groupPlan->wait_period . $groupPlan->type_of_period)->addMonth(1)->firstOfMonth();
        }
        return $eligibilityDate;
    }
}
