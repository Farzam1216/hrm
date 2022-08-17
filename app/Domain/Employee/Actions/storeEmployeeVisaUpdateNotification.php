<?php

namespace App\Domain\Employee\Actions;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Domain\Employee\Models\Employee;
use App\Domain\Employee\Models\EmployeeVisa;
use Illuminate\Support\Facades\Notification;
use App\Jobs\EmployeePersonalNotificationJob;
use App\Notifications\employeePersonalNotification;

class storeEmployeeVisaUpdateNotification
{
    public function execute($request,$id)
    {
        $data = $request->all();
        $previousData = EmployeeVisa::where('id',$id)->first();
        $employee = Employee::find($id);
        $differenceArray = array_diff($data,$previousData->toArray());
        $diferenceData = $differenceArray;
       
        $indexText =  implode(' , ',array_keys($diferenceData));

        $userName = Auth::user()->fullname;
        if( $indexText == "_token , _method"){
            $changedText = substr($indexText,16);
        }
        else{
            $changedText = substr($indexText,9);
        }

        $status = EmployeeVisa::where('id',$request->visa_type_id)->with('VisaType')->first();
        $status = $status->VisaType->visa_type;

        if($employee->gender == "Male"){
            $gender = "his";
        }
        else{
            $gender = "her";
        }

        if($employee->id == Auth::user()->id){
            $message =  "$employee->firstname $employee->lastname updated  $gender visa $status information ";
        }else{
            $userName = Auth::user()->fullname;
            $message =  "$userName updated $employee->fullname visa  $status information ";
        }

        $showMessage = $message.$changedText;
        $showMessage= str_replace("_method , ", "",  $showMessage);
        $showMessage= str_replace("country_id", "country",  $showMessage);
        $showMessage= str_replace("issue_date", "issue date",  $showMessage);
        $showMessage= str_replace("visa_type_id", "visa type",  $showMessage);
        $showMessage= str_replace("expire_date, ", "expire date",  $showMessage);
        
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

        if( $indexText != "_token"){
            EmployeePersonalNotificationJob::dispatch($employeeData,$users)->delay(Carbon::now()->addMinutes(1));
        }
    }
}
