<?php

namespace App\Domain\PerformanceReview\Actions;

use Illuminate\Support\Carbon;
use App\Domain\Employee\Models\Employee;
use Illuminate\Support\Facades\Notification;
use App\Jobs\PerformanceReviewNotificationsJob;
use App\Notifications\PerformanceReviewNotifications;

class StorePerformanceEvaluationUnassignmentNotifications
{
    public function execute($employee_id, $manager_id)
    {
        $manager = Employee::find($manager_id);
        $employee = Employee::find($employee_id);
        $message =  "Performance evaluation of $employee->firstname $employee->lastname is unassigned from $manager->firstname $manager->lastname";
        $adminUser = Employee::role('admin')->get();
        $managerUser = Employee::where('id', $manager_id)->get();
        $users =  $adminUser->merge($managerUser);
        $title = "Performance Evaluation Unassigned";
        $employeeData['title'] = $title;      
        $employeeData['employeeInfo'] = $manager;        
        $employeeData['description'] = $message;
        $employeeData['url'] = "/performance-review/evaluations";
        PerformanceReviewNotificationsJob::dispatch($employeeData,$users)->delay(Carbon::now()->addMinutes(1));
    }
}
