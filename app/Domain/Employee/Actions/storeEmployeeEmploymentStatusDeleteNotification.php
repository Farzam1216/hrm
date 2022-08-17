<?php

namespace App\Domain\Employee\Actions;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Domain\Employee\Models\Employee;
use Illuminate\Support\Facades\Notification;
use App\Jobs\EmployeePersonalNotificationJob;
use App\Notifications\employeePersonalNotification;
use App\Domain\Employee\Models\EmployeeEmploymentStatus;

class storeEmployeeEmploymentStatusDeleteNotification
{
    public function execute($request,$id)
    {
        $employeeStatusId = $request->segment(5, '');
        $previousData = EmployeeEmploymentStatus::where('id',$employeeStatusId)->with('employmentStatus')->first();
        $employee = Employee::find($id);
        $status = $previousData->employmentStatus->employment_status;
        
        if($employee->gender == "Male"){
            $gender = "his";
        }
        else{
            $gender = "her";
        }

        if($employee->id == Auth::user()->id){
            $message =  "$employee->firstname $employee->lastname deleted $gender employment status $status ";
        }else{
            $userName = Auth::user()->fullname;
            $message =  "$userName deleted $employee->fullname employment status  $status ";
        }

        $showMessage = $message;
        $adminUser = Employee::role('admin')->get();
        $hrUser = Employee::role('Hr Manager')->get();
        $users =  $adminUser->merge($hrUser);

        if($employee->isNotAdmin()){
            $user  = Employee::where('id',$id)->get();
            $users =  $adminUser->merge($user);
        }

        $employeeData['previousData'] = $employee;        
        $employeeData['description'] = $showMessage;
        $employeeData['url'] = "/employee/edit/$id";
            
        EmployeePersonalNotificationJob::dispatch($employeeData,$users)->delay(Carbon::now()->addMinutes(1));
         
    }
}
