<?php

namespace App\Console\Commands;

use App\Domain\Benefit\Models\BenefitGroup;
use App\Domain\Benefit\Models\BenefitGroupEmployee;
use App\Domain\Benefit\Models\BenefitPlan;
use App\Domain\Benefit\Models\EmployeeBenefit;
use App\Domain\Benefit\Models\EmployeeBenefitStatus;
use App\Domain\Employee\Models\Employee;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

class UpdateBenefitStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:benefit-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Employee Benefit Enrollment/Eligibility Status with Time';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * @param $employeeID
     * @param $groupPlan
     * @return bool
     */
    public function isNewlyEnrolledEmployee($employeeID, $groupPlan)
    {
        $employeeBenefits = EmployeeBenefit::where(['employee_id' => $employeeID, 'benefit_group_plan_id' => $groupPlan->id])->first();
        if ($employeeBenefits == null) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $employeeID
     * @param $groupPlanID
     * @return mixed
     */
    public function updateEmployeeBenefits($employeeID, $groupPlanID)
    {
        $employeeBenefitID = EmployeeBenefit::create(
            [
                'benefit_group_plan_id' => $groupPlanID,
                'employee_id' => $employeeID,
            ]
        );
        return $employeeBenefitID;
    }

    /**
     * @param $employeeID
     * @param $groupPlan
     * @return Carbon|string
     */

    public function calculateEmployeeBenefitEligibility($employeeID, $groupPlan)
    {
        $hiringDate = Employee::where('id', $employeeID)->pluck('joining_date')->first();
        if ($groupPlan->eligibility == "manual") {
            $eligibilityDate = null;
        } elseif ($groupPlan->eligibility == "hire_date") {
            $eligibilityDate = $hiringDate->copy();
        } elseif ($groupPlan->eligibility == "waiting_period") {
            $eligibilityDate = Carbon::parse($hiringDate . $groupPlan->wait_period . $groupPlan->type_of_period);
        } elseif ($groupPlan->eligibility == "month_after_waiting_period") {
            $eligibilityDate = Carbon::parse($hiringDate . $groupPlan->wait_period . $groupPlan->type_of_period)->addMonth(1)->firstOfMonth();
        }
        return $eligibilityDate;
    }

    /**
     * @param $statusState
     * @param $status
     * @return mixed
     */
    public function getMessageForStatusState($statusState, $status)
    {
        if ($statusState == 2) {
            return 'Plan Expired for $plan on $date';
        }
        if ($statusState == 1) {
            $message = "current_message";
        } else {
            $message = "future_message";
        }
        return DB::table('benefit_status_details')->where(['status' => $status])->pluck($message)->first();
    }

    /**
     * @param $effectiveDate
     * @param $enrollmentStatus
     * @param $planName
     * @param $statusState
     * @param string $comment
     * @return false|string
     */
    public function createStatusTrackingFieldJSON($effectiveDate, $enrollmentStatus, $planName, $statusState, $comment)
    {
        $statusMessage = $this->getMessageForStatusState($statusState, $enrollmentStatus);
        $event = str_replace(['$plan', '$date'], [$planName, $effectiveDate->format('d-m-Y')], $statusMessage);
        $status['created_by'] = Auth::user()->firstname;
        $status['statusState'] = $statusState;
        $status['event'] = $event;
        $status['comment'] = $comment;
        return json_encode($status);
    }

    /**
     * @param $plan_ID
     * @return mixed
     */
    public function getPlanName($plan_ID)
    {
        return BenefitPlan::where('id', $plan_ID)->pluck('name')->first();
    }

    /**
     * @param $employeeBenefitID
     * @param $effectiveDate
     * @param $statusBeforeEligibilityDate
     * @param $statusAfterEligibilityDate
     * @param $plan_ID
     */
    public function updateEligibility($employeeBenefitID, $effectiveDate, $statusBeforeEligibilityDate, $statusAfterEligibilityDate, $plan_ID)
    {
        $currentDate = Carbon::now()->startOfDay();
        $planName = $this->getPlanName($plan_ID);
        //If effective date is null then employee will be ineligible for benefit plan
        if ($effectiveDate == null) {
            $this->createTransaction($currentDate, $statusBeforeEligibilityDate, $planName, $employeeBenefitID, 1);
            return;
        }
        $effectiveDate = $effectiveDate->startOfDay();
        if ($currentDate->gte($effectiveDate)) {
            $this->createTransaction($effectiveDate, $statusAfterEligibilityDate, $planName, $employeeBenefitID, 1);
        } else {
            $this->createTransaction($currentDate, $statusBeforeEligibilityDate, $planName, $employeeBenefitID, 1);
            $this->createTransaction($effectiveDate, $statusAfterEligibilityDate, $planName, $employeeBenefitID, 0);
        }
    }

    /**
     * @param $effectiveDate
     * @param $status
     * @param $planName
     * @param $employeeBenefitID
     * @param $statusState
     * @param string $comment
     */
    public function createTransaction($effectiveDate, $status, $planName, $employeeBenefitID, $statusState, $comment = "Automatic Eligibility Update")
    {
        $trackingFieldData = $this->createStatusTrackingFieldJSON($effectiveDate, $status, $planName, $statusState, $comment);
        $this->createBenefitStatusTransaction($employeeBenefitID, $effectiveDate, $status, $trackingFieldData);
    }

    /**
     * @param $employeeBenefitID
     * @param $effectiveDate
     * @param $status
     * @param $trackingFieldData
     */
    public function createBenefitStatusTransaction($employeeBenefitID, $effectiveDate, $status, $trackingFieldData)
    {
        EmployeeBenefitStatus::create(
            [
                'employee_benefit_id' => $employeeBenefitID,
                'effective_date' => $effectiveDate,
                'enrollment_status' => $status,
                'enrollment_status_tracking_field' => $trackingFieldData
            ]
        );
    }

    /**
     * @param $employeeBenefitID
     * @param $status
     * @param $trackingFieldData
     */
    public function updateBenefitStatusTransaction($employeeBenefitID, $status, $trackingFieldData)
    {
        $employeeBenefitStatus = EmployeeBenefitStatus::where('employee_benefit_id', $employeeBenefitID)->where('enrollment_status', $status)->first();
        $employeeBenefitStatus->enrollment_status_tracking_field = $trackingFieldData;

        $employeeBenefitStatus->save();
    }

    /**
     * @param $plan_ID
     * @return bool
     */
    public function isGroupPlanExpired($plan_ID)
    {
        $currentDate = Carbon::now()->startOfDay();
        $benefitPlanExpiryDate = BenefitPlan::where('id', $plan_ID)->pluck('end_date')->first();
        if (!isset($benefitPlanExpiryDate) || Carbon::parse($benefitPlanExpiryDate)->startOfDay()->gte($currentDate)) {
            return false;
        } elseif (isset($benefitPlanExpiryDate) && Carbon::parse($benefitPlanExpiryDate)->startOfDay()->lte($currentDate)) {
            return true;
        }
    }

    /**
     * @param $employee_ID
     * @param $groupPlan
     * @param $employeeBenefit_ID
     */
    public function unenrollEmployeeFromPlan($employee_ID, $groupPlan, $employeeBenefit_ID)
    {
        $planName = $this->getPlanName($groupPlan->plan_id);
        $this->createTransaction(Carbon::parse($groupPlan->end_date)->startOfDay(), 'planExpired', $planName, $employeeBenefit_ID, 2, "Plan Expired");
        EmployeeBenefitStatus::where('employee_benefit_id', $employeeBenefit_ID)->delete();
        EmployeeBenefit::where(['benefit_group_plan_id' => $groupPlan->id, 'employee_id' => $employee_ID])->with('employeeBenefitStatuses')->delete();
        BenefitGroupEmployee::where(['benefit_group_id' => $groupPlan->group_id, 'employee_id' => $employee_ID])->delete();
    }
    /**
     * @param $groupEmployees
     * @return null
     */
    public function loopThroughEmployeesToCheckEligibility($groupEmployees, $groupPlan)
    {
        $currentDate = Carbon::now()->startOfDay();
        if ($groupEmployees->isEmpty()) { //Guard Clause
            return;
        } else {
            foreach ($groupEmployees as $groupEmployee) {
                if ($this->isNewlyEnrolledEmployee($groupEmployee->employee_id, $groupPlan)) {
                    $eligibilityDate = $this->calculateEmployeeBenefitEligibility($groupEmployee->employee_id, $groupPlan);
                    $employeeBenefit = $this->updateEmployeeBenefits($groupEmployee->employee_id, $groupPlan->id);
                    $this->updateEligibility($employeeBenefit->id, $eligibilityDate, 'notEligible', 'eligible', $groupPlan['plan_id']);
                    goto EmployeeBenefitTransactions;
                } else {
                    EmployeeBenefitTransactions: $employeesBenefits = $this->getEmployeeBenefitStatus($groupEmployee->employee_id, $groupPlan);
                    foreach ($employeesBenefits['employeeBenefitStatuses'] as $key => $status) {
                        if ($this->isGroupPlanExpired($groupPlan->plan_id)) {
                            $this->unenrollEmployeeFromPlan($groupEmployee->employee_id, $groupPlan, $status['employee_benefit_id']);
                            continue;
                        }
                        $effective_date = Carbon::parse($status['effective_date'])->startOfDay()->copy();
                        $enrollment_status_tracking_field = json_decode($status['enrollment_status_tracking_field']);
                        if ($currentDate->gte($effective_date)) {
                            if ($enrollment_status_tracking_field->statusState == 1) {
                                $this->deleteOldStatuses($employeesBenefits['employeeBenefitStatuses'], $key);
                                continue;
                            } else {
                                $planName = $this->getPlanName($groupPlan['plan_id']);
                                $trackingFieldData = $this->createStatusTrackingFieldJSON($effective_date, $status['enrollment_status'], $planName, 1, $status['comment']);
                                $this->updateBenefitStatusTransaction($status['employee_benefit_id'], $status['enrollment_status'], $trackingFieldData);
                                $this->deleteOldStatuses($employeesBenefits['employeeBenefitStatuses'], $key);
                            }
                        } else {
                            if ($enrollment_status_tracking_field->statusState == 0) {
                                continue;
                            } else {
                                $planName = $this->getPlanName($groupPlan['plan_id']);
                                $trackingFieldData = $this->createStatusTrackingFieldJSON($effective_date, $status['enrollment_status'], $planName, 0, $status['comment']);
                                $this->updateBenefitStatusTransaction($status['employee_benefit_id'], $status['enrollment_status'], $trackingFieldData);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * @param $statuses
     * @param $tableRowCount
     */
    public function deleteOldStatuses($statuses, $tableRowCount)
    {
        while ($tableRowCount != 0) {
            if (isset($statuses[$tableRowCount - 1])) {
                $statuses[$tableRowCount - 1]->delete();
            }
            $tableRowCount--;
        }
    }

    /**
     * @param $employeeID
     * @param $groupPlan
     * @return mixed
     */

    public function getEmployeeBenefitStatus($employeeID, $groupPlan)
    {
        $employeeBenefits = EmployeeBenefit::where(['employee_id' => $employeeID, 'benefit_group_plan_id' => $groupPlan->id])->with('employeeBenefitStatuses')->first();
        return $employeeBenefits;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \Log::info("Benefit CronJob is working fine!");
        $benefitGroupsWithEmployees = BenefitGroup::with('benefitGroupEmployee', 'groupPlans')->get();
        foreach ($benefitGroupsWithEmployees as $benefitGroup) {
            foreach ($benefitGroup['groupPlans'] as $benefitGroupPlan) {
                $this->loopThroughEmployeesToCheckEligibility($benefitGroup['benefitGroupEmployee'], $benefitGroupPlan);
            }
        }
    }
}
