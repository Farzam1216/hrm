<?php

namespace App\Domain\Attendance\Actions;

use Illuminate\Support\Facades\Notification;
use App\Domain\Employee\Models\Employee;
use App\Notifications\AttendanceCorrectionNotifications;

class StoreAttendanceCorrectionRequestDecisionNotifications
{
    public function execute($employee_id, $decision_authority_id, $date, $decision)
    {
        $decision_authority = Employee::find($decision_authority_id);
        $admin = Employee::role('admin')->get();
        $employee = Employee::where('id', $employee_id)->get();
        $title = "Attendance Correction Request Decision";
        $employeeData['title'] = $title;      
        $employeeData['employeeInfo'] = $decision_authority;
        $employeeData['url'] = "/employees/".$employee_id."/employee-attendance/".$date->format('Y-m-d');
        $date = $date->format('d-m-Y');

        if($decision == 'approved') {
            $message =  "Your attendance correction request for $date is $decision and changes are updated by $decision_authority->firstname $decision_authority->lastname";
        } else {
            $message =  "Your attendance correction request for $date is $decision by $decision_authority->firstname $decision_authority->lastname";
        }
        $employeeData['description'] = $message;
        Notification::send($employee, new AttendanceCorrectionNotifications($employeeData));

        if($decision == 'approved') {
            $message =  "Attendance correction request of ". $employee[0]->firstname. " " .$employee[0]->lastname ." for $date is $decision and changes are updated by $decision_authority->firstname $decision_authority->lastname";
        } else {
            $message =  "Attendance correction request of ". $employee[0]->firstname. " " .$employee[0]->lastname ." for $date is $decision by $decision_authority->firstname $decision_authority->lastname";
        }
        $employeeData['description'] = $message;
        Notification::send($admin, new AttendanceCorrectionNotifications($employeeData));
    }
}
