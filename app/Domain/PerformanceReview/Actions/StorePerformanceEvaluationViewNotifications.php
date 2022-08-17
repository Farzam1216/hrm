<?php

namespace App\Domain\PerformanceReview\Actions;

use Illuminate\Support\Carbon;
use App\Domain\Employee\Models\Employee;
use Illuminate\Support\Facades\Notification;
use App\Jobs\PerformanceReviewNotificationsJob;
use App\Notifications\PerformanceReviewNotifications;

class StorePerformanceEvaluationViewNotifications
{
    public function execute($questionnaire)
    {
        $employee = Employee::where('id', $questionnaire->employee_id)->get();
        if ($questionnaire->status == 1) {
            $status = 'Approved';
        } else {
            $status = 'Rejected';
        }
        $message =  "Your performance evaluation is $status and is open for you to view";
        $title = "New Performance Evaluation Decision";
        $employeeData['title'] = $title;      
        $employeeData['employeeInfo'] = $employee;        
        $employeeData['description'] = $message;
        $employeeData['url'] = "/performance-review/evaluations";
        PerformanceReviewNotificationsJob::dispatch($employeeData,$employee)->delay(Carbon::now()->addMinutes(1));
    }
}
