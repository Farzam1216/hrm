<?php

namespace App\Domain\Employee\Actions;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Domain\Employee\Models\Employee;
use App\Domain\Employee\Models\Education;
use Illuminate\Support\Facades\Notification;
use App\Jobs\EmployeePersonalNotificationJob;
use App\Notifications\employeePersonalNotification;

class storeEmployeeEducationUpdateNotification
{
    public function execute($request,$id)
    {
        $data = $request->all();
        $previousData = Education::where('id',$request->id)->with('EducationType')->first();
        $employee = Employee::find($request->employee_id);
        $differenceArray = array_diff($data,Education::where('id',$request->id)->first()->toArray());
        $diferenceData = $differenceArray;
       
        $indexText =  implode(' , ',array_keys($diferenceData));

        $userName = Auth::user()->fullname;
        if( $indexText == "_token , _method"){
            $changedText = substr($indexText,16);
        }
        else{
            $changedText = substr($indexText,9);
        }

        $status = $previousData->EducationType->education_type;
        
        if($employee->gender == "Male"){
            $gender = "his";
        }
        else{
            $gender = "her";
        }

        if($employee->id == Auth::user()->id){
            $message =  "$employee->firstname $employee->lastname updated  $gender education $status ";
        }else{
            $userName = Auth::user()->fullname;
            $message =  "$userName updated $employee->fullname education  $status ";
        }

        $showMessage = $message.$changedText;
        $showMessage= str_replace("_method , ", "",  $showMessage);
        $showMessage= str_replace("_token , ", "",  $showMessage);
        $showMessage= str_replace("date_end", "end date",  $showMessage);
        $showMessage= str_replace("institute_name", "institute name",  $showMessage);
        $showMessage= str_replace("date_start", "start date",  $showMessage);
        $showMessage= str_replace("education_type_id", "education type",  $showMessage);
        
        $adminUser = Employee::role('admin')->get();
        $hrUser = Employee::role('Hr Manager')->get();
        $users =  $adminUser->merge($hrUser);
        
        if($employee->isNotAdmin()){
            $user  = Employee::where('id',$request->employee_id)->get();
            $users =  $users->merge($user);
        }
        
        $employeeData['previousData'] = $employee;        
        $employeeData['description'] = $showMessage;
        $employeeData['url'] = "/employee/edit/$request->employee_id";
        
        if( $indexText != "_token"){
            EmployeePersonalNotificationJob::dispatch($employeeData,$users)->delay(Carbon::now()->addMinutes(1));
        }
        
    }
}
