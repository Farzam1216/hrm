<?php

namespace App\Domain\PerformanceReview\Actions;

use Illuminate\Support\Carbon;
use App\Domain\Employee\Models\Employee;
use Illuminate\Support\Facades\Notification;
use App\Jobs\PerformanceReviewNotificationsJob;
use App\Notifications\PerformanceReviewNotifications;

class StorePerformanceEvaluationAssignmentNotifications
{
    public function execute($employee_id, $manager_id)
    {
        $manager = Employee::find($manager_id);
        $employee = Employee::find($employee_id);
        $message =  "Performance evaluation of $employee->firstname $employee->lastname is assigned to $manager->firstname $manager->lastname";
        $adminUser = Employee::role('admin')->get();
        $managerUser = Employee::where('id', $manager_id)->get();
        $title = "New Performance Evaluation";
        $employeeData['title'] = $title;
        $employeeData['employeeInfo'] = $manager;
        $employeeData['description'] = $message;
        $employeeData['url'] = "/performance-review/evaluations";
        PerformanceReviewNotificationsJob::dispatch($employeeData,$adminUser)->delay(Carbon::now()->addMinutes(1));
        $message =  "Performance evaluation of $employee->firstname $employee->lastname is assigned to you. Please complete it";
        $employeeData['description'] = $message;
        PerformanceReviewNotificationsJob::dispatch($employeeData,$managerUser)->delay(Carbon::now()->addMinutes(1));
    }
}
