<?php

namespace App\Domain\Employee\Actions;

use App\Jobs\EmployeePersonalNotificationJob;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Domain\Employee\Models\Employee;

class storeEmployeeNotifications
{
    public function execute($data,$previousInfo)
    {
        $employee =  $previousInfo->toArray();
        $differenceArray = array_diff($data,$employee);
        $diferenceData = $differenceArray;
        $changeText =  implode(' , ',array_keys($diferenceData));
        
        $userName = Auth::user()->fullname;
        
        if( $changeText == "_token"){
            $changeText = substr($changeText,9);
        }

        if($previousInfo->id == Auth::user()->id){
            $message =  "$previousInfo->firstname $previousInfo->lastname changed his ";
        }else{
            $userName = Auth::user()->fullname;
            $message =  "$userName changed $previousInfo->fullname ";
        }

        $showMessage = $message.$changeText;
        $showMessage= str_replace("_method , ", "",  $showMessage);
        $showMessage= str_replace("_token , ", "",  $showMessage);
        $showMessage= str_replace("contact_no", "contact number",  $showMessage);
        $showMessage= str_replace("emergency_contact", "emergency contact number",  $showMessage);
        $showMessage= str_replace("current_address", "current address",  $showMessage);
        $showMessage= str_replace("permanent_address", "permanent address",  $showMessage);
        $showMessage= str_replace("firstname", "first name",  $showMessage);
        $showMessage= str_replace("lastname", "last name",  $showMessage);
        $showMessage= str_replace("official_email", "official email",  $showMessage);
        $showMessage= str_replace("personal_email", "personal email",  $showMessage);
        $showMessage= str_replace("date_of_birth", "date of birth",  $showMessage);
        $showMessage= str_replace("joining_date", "joining date",  $showMessage);
        $showMessage= str_replace("exit_date", "exit date",  $showMessage);
        $showMessage= str_replace("work-schedule-id", "work schedule",  $showMessage);
        $showMessage= str_replace("marital_status", "marital status",  $showMessage);
        $showMessage= str_replace("department_id", "department",  $showMessage);
        $showMessage= str_replace("location_id", "location",  $showMessage);
        $showMessage= str_replace("attendance_permission", "attendance permission",  $showMessage);
        
        $adminUser = Employee::role('admin')->get();
        $hrUser = Employee::role('Hr Manager')->get();
        $users =  $adminUser->merge($hrUser);

        if($previousInfo->isNotAdmin()){
            $user  = Employee::where('id',$previousInfo->id)->get();
            $users =  $users->merge($user);
        }
        
        $employeeData['previousData'] = $previousInfo;        
        $employeeData['description'] = $showMessage;
        $employeeData['url'] = "/employee/edit/$previousInfo->id";  
        
        if($changeText){
            EmployeePersonalNotificationJob::dispatch($employeeData,$users)->delay(Carbon::now()->addMinutes(1));
        }

    }
}
