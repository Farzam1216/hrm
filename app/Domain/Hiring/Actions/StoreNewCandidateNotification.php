<?php

namespace App\Domain\Hiring\Actions;

use Illuminate\Support\Carbon;
use App\Jobs\HiringNotificationsJob;
use Illuminate\Support\Facades\Auth;
use App\Domain\Employee\Models\Employee;
use App\Domain\Hiring\Models\JobOpening;
use App\Notifications\hiringNotifications;
use Illuminate\Support\Facades\Notification;

class StoreNewCandidateNotification
{
    public function execute($request)
    {
        $job = JobOpening::find($request->position);
        $employeeInfo = Employee::find(Auth::user()->id);
        $message =  "$request->name applied for $job->title.";
        $adminUser = Employee::role('admin')->get();
        $hrUser = Employee::role('Hr Manager')->get();
        $users =  $adminUser->merge($hrUser);
        $title = "New Job Applicant Notification";
        $employeeData['employeeInfo'] = $employeeInfo; 
        $employeeData['title'] = $title;         
        $employeeData['description'] = $message;
        $employeeData['url'] = "/candidates";
        HiringNotificationsJob::dispatch($employeeData,$users)->delay(Carbon::now()->addMinutes(1));
    }
}
