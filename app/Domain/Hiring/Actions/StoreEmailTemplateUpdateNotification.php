<?php

namespace App\Domain\Hiring\Actions;

use Illuminate\Support\Carbon;
use App\Jobs\HiringNotificationsJob;
use Illuminate\Support\Facades\Auth;
use App\Domain\Employee\Models\Employee;
use App\Notifications\hiringNotifications;
use App\Domain\Hiring\Models\EmailTemplate;
use Illuminate\Support\Facades\Notification;

class StoreEmailTemplateUpdateNotification
{
    public function execute($request,$id)
    {
        $data = $request->all();
        $differenceArray = array_diff($data,EmailTemplate::where('id',$id)->first()->toArray());
        $diferenceData = $differenceArray;
        $indexText =  implode(' , ',array_keys($diferenceData));
        $employeeInfo = Employee::find(Auth::user()->id);
        $userName = Auth::user()->fullname;
        $message =  "$userName updated email template ($request->template_name).";
        $adminUser = Employee::role('admin')->get();
        $title = "Update Email Template Notification";
        $employeeData['title'] = $title;      
        $employeeData['employeeInfo'] = $employeeInfo;        
        $employeeData['description'] = $message;
        $employeeData['url'] = "/email-templates";
       
        if( $indexText != "_method , _token"){
            HiringNotificationsJob::dispatch($employeeData,$adminUser)->delay(Carbon::now()->addMinutes(1));
        }
    }
}
