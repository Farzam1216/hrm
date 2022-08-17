<?php

namespace App\Domain\Hiring\Actions;

use Illuminate\Support\Carbon;
use App\Jobs\HiringNotificationsJob;
use Illuminate\Support\Facades\Auth;
use App\Domain\Employee\Models\Employee;
use App\Notifications\hiringNotifications;
use Illuminate\Support\Facades\Notification;

class StoreDivisionNotification
{
    public function execute($request)
    {
        $employeeInfo = Employee::find(Auth::user()->id);
        $userName = Auth::user()->fullname;
        $message =  "$userName added new job division ($request->division_name).";
        $adminUser = Employee::role('admin')->get();
        $hrUser = Employee::role('Hr Manager')->get();
        $users =  $adminUser->merge($hrUser);
        $title = "New Division Notification";
        $employeeData['title'] = $title;      
        $employeeData['employeeInfo'] = $employeeInfo;        
        $employeeData['description'] = $message;
        $employeeData['url'] = "/divisions";
        HiringNotificationsJob::dispatch($employeeData,$users)->delay(Carbon::now()->addMinutes(1));
    }
}
