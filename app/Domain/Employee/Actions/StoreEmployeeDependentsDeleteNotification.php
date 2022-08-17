<?php

namespace App\Domain\Employee\Actions;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Domain\Employee\Models\Employee;
use Illuminate\Support\Facades\Notification;
use App\Jobs\EmployeePersonalNotificationJob;
use App\Domain\Benefit\Models\EmployeeDependent;
use App\Notifications\employeePersonalNotification;

class StoreEmployeeDependentsDeleteNotification
{
    public function execute($request,$id,$dependentId)
    {
        $previousInfo = Employee::find($id);
        $employeeDependent = EmployeeDependent::find($dependentId);

        if($previousInfo->gender == "Male"){
            $gender = "his";
        }
        else{
            $gender = "her";
        }

        $dependent = $employeeDependent->first_name;

        if($previousInfo->id == Auth::user()->id){
            $message =  "$previousInfo->firstname$previousInfo->lastname deleted $gender employee dependents ($dependent).";
        }else{
            $userName = Auth::user()->fullname;
            $message =  "$userName deleted $previousInfo->fullname employee dependents ($dependent).";
        }
        
        $showMessage = $message;
        $adminUser = Employee::role('admin')->get();
        $hrUser = Employee::role('Hr Manager')->get();
        $users =  $adminUser->merge($hrUser);

        if($previousInfo->isNotAdmin()){
            $user  = Employee::where('id',$id)->get();
            $users =  $users->merge($user);
        }

        $employeeData['previousData'] = $previousInfo;        
        $employeeData['description'] = $showMessage;
        $employeeData['url'] = "/employees/$id/dependents";

        EmployeePersonalNotificationJob::dispatch($employeeData,$users)->delay(Carbon::now()->addMinutes(1));
        
    }
}
