<?php

namespace App\Domain\Employee\Actions;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Domain\Employee\Models\Employee;
use App\Domain\Employee\Models\EmployeeVisa;
use Illuminate\Support\Facades\Notification;
use App\Jobs\EmployeePersonalNotificationJob;
use App\Notifications\employeePersonalNotification;

class storeEmployeeVisaDeleteNotification
{
    public function execute($request,$id)
    {
        $previousData = EmployeeVisa::where('id',$id)->first();
        $employee = Employee::find($previousData->employee_id);
        $previousInfo = Employee::find($employee->id);

        if($previousInfo->gender == "Male"){
            $gender = "his";
        }
        else{
            $gender = "her";
        }

        if($previousInfo->id == Auth::user()->id){
            $message =  "$previousInfo->firstname $previousInfo->lastname deleted $gender visa details.";
        }else{
            $userName = Auth::user()->fullname;
            $message =  "$userName deleted $previousInfo->fullname visa details.";
        }

        $showMessage = $message;
        $adminUser = Employee::role('admin')->get();
        $hrUser = Employee::role('Hr Manager')->get();
        $users =  $adminUser->merge($hrUser);

        if($previousInfo->isNotAdmin()){
            $user  = Employee::where('id',$previousData->employee_id)->get();
            $users =  $users->merge($user);
        }

        $employeeData['previousData'] = $previousInfo;        
        $employeeData['description'] = $showMessage;
        $employeeData['url'] = "/employee/edit/$previousData->employee_id";
        EmployeePersonalNotificationJob::dispatch($employeeData,$users)->delay(Carbon::now()->addMinutes(1));
    }
}
