<?php

namespace App\Domain\TimeOff\Actions;

use Illuminate\Support\Facades\Notification;
use App\Domain\Employee\Models\Employee;
use App\Notifications\TimeOffNotifications;

class StoreTodayApprovedTimeOffNotifications
{
    public function execute($employee_id)
    {
        $employee = Employee::find($employee_id);
        $manager = $employee->manager($employee->manager_id);
        $message =  "$employee->firstname $employee->lastname is on approved time off today";
        $adminUser = Employee::role('admin')->get();
        $hrManagerUser = Employee::role('Hr Manager')->get();
        $users =  $adminUser->merge($hrManagerUser);
        
        if (isset($manager->id)) {
            $directManagerUser = Employee::where('id', $manager->id)->get();
            $users =  $users->merge($directManagerUser);

            if ($manager->manager_id) {
                $indirectManagerUser = Employee::where('id', $manager->manager_id)->get();
                $users =  $users->merge($indirectManagerUser);
            }
        }

        $title = "Today's Approved Time Off Notification";
        $employeeData['title'] = $title;
        $employeeData['employeeInfo'] = $employee;
        $employeeData['description'] = $message;
        $employeeData['url'] = "/employee/".$employee->id."/timeoff";
        Notification::send($users, new TimeOffNotifications($employeeData));
    }
}
