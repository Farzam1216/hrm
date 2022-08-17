<?php

namespace App\Domain\Employee\Actions;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Domain\Employee\Models\Employee;
use Illuminate\Support\Facades\Notification;
use App\Jobs\EmployeePersonalNotificationJob;
use App\Notifications\employeePersonalNotification;

class storeUserAccountNotificaiton
{
    public function execute($request)
    {
        $data = $request->all();
        $employee = Employee::find($request->id);
        $differenceArray = array_diff($data,Employee::where('id',$request->id)->first()->toArray());
        $diferenceData = $differenceArray;
       
        $indexText =  implode(' , ',array_keys($diferenceData));

        $userName = Auth::user()->fullname;

        if( $indexText == "_token , _method"){
            $changedText = substr($indexText,16);
        }
        else{
            $changedText = substr($indexText,9);
        }
        
        if($employee->gender == "Male"){
            $gender = "his";
        }
        else{
            $gender = "her";
        }

        if($employee->id == Auth::user()->id){
            $message =  "$employee->firstname $employee->lastname updated $gender ";
        }else{
            $userName = Auth::user()->fullname;
            $message =  "$userName updated $employee->fullname ";
        }

        $showMessage = $message.$changedText;
        $showMessage= str_replace("_method , ", "",  $showMessage);
        $showMessage= str_replace("_token , ", "",  $showMessage);
        $showMessage= str_replace(" new_password_confirmation", "",  $showMessage);
        $showMessage= str_replace(", new_password ,", "",  $showMessage);
        $showMessage= str_replace("current_password", "password",  $showMessage);
        $showMessage= str_replace("first_name", "first name",  $showMessage);
        $showMessage= str_replace("last_name", "last name",  $showMessage);

        $adminUser = Employee::role('admin')->get();
        $hrUser = Employee::role('Hr Manager')->get();
        $users =  $adminUser->merge($hrUser);

        if($employee->isNotAdmin()){
            $user  = Employee::where('id',$request->id)->get();
            $users =  $users->merge($user);
        }

        $employeeData['previousData'] = $employee;        
        $employeeData['description'] = $showMessage;
        $employeeData['url'] = "/personal-profile";

        if( $indexText != "_token"){
            EmployeePersonalNotificationJob::dispatch($employeeData,$users)->delay(Carbon::now()->addMinutes(1));
        }
        
    }
}
