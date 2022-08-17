<?php

namespace App\Domain\Employee\Actions;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Domain\Employee\Models\Asset;
use App\Domain\Employee\Models\Employee;
use Illuminate\Support\Facades\Notification;
use App\Jobs\EmployeePersonalNotificationJob;
use App\Notifications\employeePersonalNotification;

class storeEmployeeAssetUpdateNotification
{
    public function execute($request,$id)
    {
        $data = $request->all();
        $employee = Employee::find($request->employee_id);
        $differenceArray = array_diff($data,Asset::where('id',$id)->first()->toArray());
        $diferenceData = $differenceArray;
       
        $indexText =  implode(' , ',array_keys($diferenceData));

        $userName = Auth::user()->fullname;
        if( $indexText == "_token , _method"){
            $changedText = substr($indexText,16);
        }
        else{
            $changedText = substr($indexText,9);
        }

        $status = $request->serial;
        
        if($employee->gender == "Male"){
            $gender = "his";
        }
        else{
            $gender = "her";
        }

        if($employee->id == Auth::user()->id){
            $message =  "$employee->firstname $employee->lastname updated $gender employee asset serial ($status) ";
        }else{
            $userName = Auth::user()->fullname;
            $message =  "$userName updated $employee->fullname employee asset serial ($status) ";
        }

        $showMessage = $message.$changedText;
        $showMessage= str_replace("_method , ", "",  $showMessage);
        $showMessage= str_replace("_token , ", "",  $showMessage);
        $showMessage= str_replace("asset_description", "asset description",  $showMessage);
        $showMessage= str_replace("asset_category", "asset category",  $showMessage);
        $showMessage= str_replace("assign_date", "assign date",  $showMessage);
        $showMessage= str_replace("return_date", "return date",  $showMessage);

        $adminUser = Employee::role('admin')->get();
        $hrUser = Employee::role('Hr Manager')->get();
        $users =  $adminUser->merge($hrUser);

        if($employee->isNotAdmin()){
            $user  = Employee::where('id',$request->employee_id)->get();
            $users =  $users->merge($user);
        }

        $employeeData['previousData'] = $employee;        
        $employeeData['description'] = $showMessage;
        $employeeData['url'] = "/employees/$request->employee_id/assets";
        
        if( $indexText != "_token , _method"){
            EmployeePersonalNotificationJob::dispatch($employeeData,$users)->delay(Carbon::now()->addMinutes(1));
        }
         
    }
}
