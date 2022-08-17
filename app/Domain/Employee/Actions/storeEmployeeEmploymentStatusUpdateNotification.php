<?php

namespace App\Domain\Employee\Actions;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Domain\Employee\Models\Employee;
use Illuminate\Support\Facades\Notification;
use App\Jobs\EmployeePersonalNotificationJob;
use App\Notifications\employeePersonalNotification;
use App\Domain\Employee\Models\EmployeeEmploymentStatus;

class storeEmployeeEmploymentStatusUpdateNotification
{
    public function execute($request,$id)
    {
        $data = $request->all();
        $employeeStatusId = $request->segment(5, '');
        $previousData = EmployeeEmploymentStatus::where('id',$employeeStatusId)->with('employmentStatus')->first();
        $employee = Employee::find($id);
        $differenceArray = array_diff($data,EmployeeEmploymentStatus::where('id',$employeeStatusId)->first()->toArray());
        $diferenceData = $differenceArray;
       
        $indexText =  implode(' , ',array_keys($diferenceData));

        $userName = Auth::user()->fullname;

        if( $indexText == "_token , _method"){
            $changedText = substr($indexText,16);
        }
        else{
            $changedText = substr($indexText,9);
        }

        $status = $previousData->employmentStatus->employment_status;
        
        if($employee->gender == "Male"){
            $gender = "his";
        }
        else{
            $gender = "her";
        }

        if($employee->id == Auth::user()->id){
            $message =  "$employee->firstname $employee->lastname updated  $gender employment status $status ";
        }else{
            $userName = Auth::user()->fullname;
            $message =  "$userName updated $employee->fullname employment status  $status ";
        }

        $showMessage = $message.$changedText;
        $showMessage= str_replace("_method , ", "",  $showMessage);
        $showMessage= str_replace("effective_date", "effective date",  $showMessage);
        $showMessage= str_replace("employment_status", "employment status",  $showMessage);

        $adminUser = Employee::role('admin')->get();
        $hrUser = Employee::role('Hr Manager')->get();
        $users =  $adminUser->merge($hrUser);

        if($employee->isNotAdmin()){
            $user  = Employee::where('id',$id)->get();
            $users =  $users->merge($user);
        }

        $employeeData['previousData'] = $employee;        
        $employeeData['description'] = $showMessage;
        $employeeData['url'] = "/employee/edit/$id";

        if( $indexText != "_token , _method"){
            EmployeePersonalNotificationJob::dispatch($employeeData,$users)->delay(Carbon::now()->addMinutes(1));
        }
         
    }
}
