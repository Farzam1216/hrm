<?php

namespace App\Domain\SmtpDetail\Actions;

use Illuminate\Support\Facades\Notification;
use App\Domain\Employee\Models\Employee;
use App\Notifications\MailConfigurationUpdatedNotifications;

class StoreMailConfigurationUpdatedNotification
{
    public function execute($employee)
    {
        $message =  "Mail configuration is updated by $employee->firstname $employee->lastname";
        $adminUser = Employee::role('admin')->get();
        $title = "Mail Configuration Updated";
        $employeeData['title'] = $title;      
        $employeeData['employeeInfo'] = $employee;        
        $employeeData['description'] = $message;
        $employeeData['url'] = "/smtp-details";
        Notification::send($adminUser, new MailConfigurationUpdatedNotifications($employeeData));
    }
}
