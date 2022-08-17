<?php

namespace App\Domain\Hiring\Actions;

use Illuminate\Support\Carbon;
use App\Jobs\HiringNotificationsJob;
use Illuminate\Support\Facades\Auth;
use App\Domain\Employee\Models\Employee;
use App\Domain\Hiring\Models\JobOpening;
use App\Notifications\hiringNotifications;
use Illuminate\Support\Facades\Notification;

class StoreJobDeleteNotifications
{
    public function execute($id)
    {
        $previousData = JobOpening::where('id',$id)->first();
        $employeeInfo = Employee::find(Auth::user()->id);
        $userName = Auth::user()->fullname;
        $message =  "$userName deleted job ($previousData->title).";
        $adminUser = Employee::role('admin')->get();
        $hrUser = Employee::role('Hr Manager')->get();
        $users =  $adminUser->merge($hrUser);
        $title = "Delete Job Opening Notification";
        $employeeData['employeeInfo'] = $employeeInfo;
        $employeeData['title'] = $title;                
        $employeeData['description'] = $message;
        $employeeData['url'] = "/job";
        HiringNotificationsJob::dispatch($employeeData,$users)->delay(Carbon::now()->addMinutes(1));
    }
}
