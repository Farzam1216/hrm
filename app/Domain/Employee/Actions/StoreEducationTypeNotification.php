<?php

namespace App\Domain\Employee\Actions;

use Illuminate\Support\Carbon;
use App\Jobs\HiringNotificationsJob;
use Illuminate\Support\Facades\Auth;
use App\Domain\Employee\Models\Employee;
use App\Notifications\hiringNotifications;
use Illuminate\Support\Facades\Notification;

class StoreEducationTypeNotification
{
    public function execute($request)
    {
        $employeeInfo = Employee::find(Auth::user()->id);
        $userName = Auth::user()->fullname;
        $message =  "$userName added new education type ($request->education_type).";
        $adminUser = Employee::role('admin')->get();
        $title = "New Employee Education Type Notification";
        $employeeData['title'] = $title;      
        $employeeData['employeeInfo'] = $employeeInfo;        
        $employeeData['description'] = $message;
        $employeeData['url'] = "/education-types";
        HiringNotificationsJob::dispatch($employeeData,$adminUser)->delay(Carbon::now()->addMinutes(1));
    }
}
