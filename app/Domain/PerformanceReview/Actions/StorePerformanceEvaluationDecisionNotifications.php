<?php

namespace App\Domain\PerformanceReview\Actions;

use Illuminate\Support\Carbon;
use App\Domain\Employee\Models\Employee;
use Illuminate\Support\Facades\Notification;
use App\Jobs\PerformanceReviewNotificationsJob;
use App\Notifications\PerformanceReviewNotifications;

class StorePerformanceEvaluationDecisionNotifications
{
    public function execute($questionnaire)
    {
        $manager = Employee::find($questionnaire->submitter_id);
        $employee = Employee::find($questionnaire->employee_id);
        $hrManager = Employee::find($questionnaire->decision_authority_id);
        if ($questionnaire->status == 1) {
            $status = 'Approved';
        } else {
            $status = 'Rejected';
        }
        $message =  "Performance evaluation of $employee->firstname $employee->lastname is $status by $hrManager->firstname $hrManager->lastname";
        $adminUser = Employee::role('admin')->get();
        $managerUser = Employee::where('id', $questionnaire->submitter_id)->get();
        $users =  $adminUser->merge($managerUser);
        $title = "New Performance Evaluation Decision";
        $employeeData['title'] = $title;      
        $employeeData['employeeInfo'] = $manager;        
        $employeeData['description'] = $message;
        $employeeData['url'] = "/performance-review/evaluations";
        PerformanceReviewNotificationsJob::dispatch($employeeData,$users)->delay(Carbon::now()->addMinutes(1));
    }
}
