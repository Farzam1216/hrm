<?php

namespace App\Domain\Hiring\Actions;

use Illuminate\Support\Carbon;
use App\Jobs\HiringNotificationsJob;
use Illuminate\Support\Facades\Auth;
use App\Domain\Hiring\Models\Candidate;
use App\Domain\Employee\Models\Employee;
use App\Notifications\hiringNotifications;
use Illuminate\Support\Facades\Notification;

class StoreCandidateRatingNotifications
{
    public function execute($request)
    {
        $candidate = Candidate::find($request->candidate_id);
        $employeeInfo = Employee::find(Auth::user()->id);
        $userName = Auth::user()->fullname;
        $message =  "$userName update candidate ($candidate->name) $request->change_in.";
        $adminUser = Employee::role('admin')->get();
        $hrUser = Employee::role('Hr Manager')->get();
        $users =  $adminUser->merge($hrUser);
        $title = "New Candidate Status Notification";
        $employeeData['employeeInfo'] = $employeeInfo; 
        $employeeData['title'] = $title;            
        $employeeData['description'] = $message;
        $employeeData['url'] = "/candidate/$request->candidate_id";
        HiringNotificationsJob::dispatch($employeeData,$users)->delay(Carbon::now()->addMinutes(1));
    }
}
