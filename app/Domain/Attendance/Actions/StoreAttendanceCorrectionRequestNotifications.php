<?php

namespace App\Domain\Attendance\Actions;

use Illuminate\Support\Facades\Notification;
use App\Domain\Employee\Models\Employee;
use App\Notifications\AttendanceCorrectionNotifications;

class StoreAttendanceCorrectionRequestNotifications
{
    public function execute($employee_id)
    {
        $employee = Employee::find($employee_id);
        $message =  "Attendance correction request is submitted by $employee->firstname $employee->lastname";
        $adminUser = Employee::role('admin')->get();
        $hrManagerUser = Employee::role('Hr Manager')->get();
        $users =  $adminUser->merge($hrManagerUser);
        $title = "New Attendance Correction Request";
        $employeeData['title'] = $title;      
        $employeeData['employeeInfo'] = $employee;        
        $employeeData['description'] = $message;
        $employeeData['url'] = "/correction-requests";
        Notification::send($users, new AttendanceCorrectionNotifications($employeeData));
    }
}
