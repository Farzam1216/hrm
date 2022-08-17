<?php

namespace App\Domain\Employee\Actions;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Domain\Employee\Models\Employee;
use App\Domain\Employee\Models\EmployeeJob;
use Illuminate\Support\Facades\Notification;
use App\Jobs\EmployeePersonalNotificationJob;
use App\Notifications\employeePersonalNotification;

class storeEmployeeJobUpdateNotification
{
    public function execute($request,$id)
    {
        $data = $request->all();
        $employeeJobId = $request->segment(5, '');
        $previousData = EmployeeJob::where('id',$employeeJobId)->first();
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

        $jobTitle = $previousData->designation->designation_name;

        if($employee->gender == "Male"){
            $gender = "his";
        }
        else{
            $gender = "her";
        }

        if($employee->id == Auth::user()->id){
            $message =  "$employee->firstname $employee->lastname updated $gender job $jobTitle information ";
        }else{
            $userName = Auth::user()->fullname;
            $message =  "$userName updated $employee->fullname job $jobTitle information ";
        }

        $showMessage = $message.$changedText;
        $showMessage= str_replace("_method , ", "",  $showMessage);
        $showMessage= str_replace("_token , ", "",  $showMessage);
        $showMessage= str_replace("effective_date", "effective date",  $showMessage);
        $showMessage= str_replace("location_id", "location",  $showMessage);
        $showMessage= str_replace("designation_id", "job title",  $showMessage);
        $showMessage= str_replace("report_to", "report to",  $showMessage);
        $showMessage= str_replace("department_id , ", "department",  $showMessage);
        $showMessage= str_replace("division_id", "division",  $showMessage);
        
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
