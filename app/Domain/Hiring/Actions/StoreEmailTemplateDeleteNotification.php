<?php

namespace App\Domain\Hiring\Actions;

use Illuminate\Support\Carbon;
use App\Jobs\HiringNotificationsJob;
use Illuminate\Support\Facades\Auth;
use App\Domain\Employee\Models\Employee;
use App\Notifications\hiringNotifications;
use App\Domain\Hiring\Models\EmailTemplate;
use Illuminate\Support\Facades\Notification;

class StoreEmailTemplateDeleteNotification
{
    public function execute($id)
    {
        $data = EmailTemplate::find($id);
        $employeeInfo = Employee::find(Auth::user()->id);
        $userName = Auth::user()->fullname;
        $message =  "$userName deleted email template ($data->template_name).";
        $adminUser = Employee::role('admin')->get();
        $title = "Delete Email Template Notification";
        $employeeData['title'] = $title;      
        $employeeData['employeeInfo'] = $employeeInfo;        
        $employeeData['description'] = $message;
        $employeeData['url'] = "/email-templates";
        HiringNotificationsJob::dispatch($employeeData,$adminUser)->delay(Carbon::now()->addMinutes(1));
    }
}
