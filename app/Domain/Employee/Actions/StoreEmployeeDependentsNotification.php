<?php

namespace App\Domain\Employee\Actions;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Domain\Employee\Models\Employee;
use Illuminate\Support\Facades\Notification;
use App\Jobs\EmployeePersonalNotificationJob;
use App\Notifications\employeePersonalNotification;

class StoreEmployeeDependentsNotification
{
    public function execute($request,$id)
    {
        $previousInfo = Employee::find($id);

        if($previousInfo->gender == "Male"){
            $gender = "his";
        }
        else{
            $gender = "her";
        }

        if($previousInfo->id == Auth::user()->id){
            $message =  "$previousInfo->firstname$previousInfo->lastname added $gender new employee dependents.";
        }else{
            $userName = Auth::user()->fullname;
            $message =  "$userName added $previousInfo->fullname new employee dependents.";
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
