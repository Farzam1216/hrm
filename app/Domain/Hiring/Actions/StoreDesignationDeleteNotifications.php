<?php

namespace App\Domain\Hiring\Actions;

use Illuminate\Support\Carbon;
use App\Jobs\HiringNotificationsJob;
use Illuminate\Support\Facades\Auth;
use App\Domain\Employee\Models\Employee;
use App\Notifications\hiringNotifications;
use App\Domain\Employee\Models\Designation;
use Illuminate\Support\Facades\Notification;

class StoreDesignationDeleteNotifications
{
    public function execute($id)
    {   
        $data = Designation::where('id',$id)->first();
        $employeeInfo = Employee::find(Auth::user()->id);
        $userName = Auth::user()->fullname;
        $message =  "$userName deleted job designation ($data->designation_name).";
        $adminUser = Employee::role('admin')->get();
        $hrUser = Employee::role('Hr Manager')->get();
        $users =  $adminUser->merge($hrUser);
        $title = "Delete Deignation Notification";
        $employeeData['title'] = $title;      
        $employeeData['employeeInfo'] = $employeeInfo;        
        $employeeData['description'] = $message;
        $employeeData['url'] = "/designations";
        HiringNotificationsJob::dispatch($employeeData,$users)->delay(Carbon::now()->addMinutes(1));
    }
}
