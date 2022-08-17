<?php

namespace App\Domain\Employee\Actions;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Domain\Employee\Models\Employee;
use Illuminate\Support\Facades\Notification;
use App\Jobs\EmployeePersonalNotificationJob;
use App\Domain\Benefit\Models\EmployeeDependent;
use App\Notifications\employeePersonalNotification;

class StoreEmployeeDependentsUpdateNotification
{
    public function execute($request,$id,$dependentID)
    {
        $data = $request->all();
        $employee = Employee::find($id);
        $differenceArray = array_diff($data,EmployeeDependent::where('id',$dependentID)->first()->toArray());
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
            $message =  "$employee->firstname $employee->lastname updated $gender employee dependents ";
        }else{
            $userName = Auth::user()->fullname;
            $message =  "$userName updated $employee->fullname employee dependents ";
        }

        $showMessage = $message.$changedText;
        $showMessage= str_replace("_token , ", "",  $showMessage);
        $showMessage= str_replace("_method , ", "",  $showMessage);
        $showMessage= str_replace("first_name", "first name",  $showMessage);
        $showMessage= str_replace("middle_name", "middle name",  $showMessage);
        $showMessage= str_replace("last_name", "last name",  $showMessage);
        $showMessage= str_replace("birth_date", "birth date",  $showMessage);
        $showMessage= str_replace("date_of_birth", "date of birth",  $showMessage);
        $showMessage= str_replace("snn_number", "snn number",  $showMessage);
        $showMessage= str_replace("sin_number", "sin number",  $showMessage);
        $showMessage= str_replace("SIN", "SIN",  $showMessage);
        $showMessage= str_replace("home_phone", "home phone",  $showMessage);
        $showMessage= str_replace("home_number", "home number",  $showMessage);

        $adminUser = Employee::role('admin')->get();
        $hrUser = Employee::role('Hr Manager')->get();
        $users =  $adminUser->merge($hrUser);

        if($employee->isNotAdmin()){
            $user  = Employee::where('id',$id)->get();
            $users =  $users->merge($user);
        }

        $employeeData['previousData'] = $employee;        
        $employeeData['description'] = $showMessage;
        $employeeData['url'] = "/employees/$id/dependents";

        if( $indexText != "_method , _token"){
            EmployeePersonalNotificationJob::dispatch($employeeData,$users)->delay(Carbon::now()->addMinutes(1));
        }
        
    }
}
