<?php

namespace App\Domain\Employee\Actions;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Domain\Employee\Models\Employee;
use App\Domain\Employee\Models\EmployeeJob;
use Illuminate\Support\Facades\Notification;
use App\Jobs\EmployeePersonalNotificationJob;
use App\Notifications\employeePersonalNotification;

class storeEmployeeJobDeleteNotification
{
    public function execute($request,$id)
    {
        $employeeJobId = $request->segment(5, '');
        $previousData = EmployeeJob::where('id',$employeeJobId)->first();
        $employee = Employee::find($id);
        $userName = Auth::user()->fullname;
        $jobTitle = $previousData->designation->designation_name;

        if($employee->gender == "Male"){
            $gender = "his";
        }
        else{
            $gender = "her";
        }

        if($employee->id == Auth::user()->id){
            $message =  "$employee->firstname $employee->lastname deleted  $gender job $jobTitle information ";
        }else{
            $userName = Auth::user()->fullname;
            $message =  "$userName deleted $employee->fullname job $jobTitle information ";
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
