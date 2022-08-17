<?php

namespace App\Domain\Hiring\Actions;

use Illuminate\Support\Carbon;
use App\Jobs\HiringNotificationsJob;
use Illuminate\Support\Facades\Auth;
use App\Domain\Employee\Models\Employee;
use App\Notifications\hiringNotifications;
use App\Domain\Hiring\Models\EmailTemplate;
use Illuminate\Support\Facades\Notification;

class StoreWelcomeEmailTemplateNotification
{
    public function execute($request)
    {
        $data = EmailTemplate::find($request->id);
        $employeeInfo = Employee::find(Auth::user()->id);
        $userName = Auth::user()->fullname;
        $message =  "$userName set ($data->template_name) email template as welcome email template.";
        $adminUser = Employee::role('admin')->get();
        $title = "New Job Opening Notification";
        $employeeData['title'] = $title;      
        $employeeData['employeeInfo'] = $employeeInfo;        
        $employeeData['description'] = $message;
        $employeeData['url'] = "/email-templates";
        HiringNotificationsJob::dispatch($employeeData,$adminUser)->delay(Carbon::now()->addMinutes(1));
    }
}
