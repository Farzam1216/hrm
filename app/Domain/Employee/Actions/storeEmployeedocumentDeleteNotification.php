<?php

namespace App\Domain\Employee\Actions;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Domain\Employee\Models\Employee;
use Illuminate\Support\Facades\Notification;
use App\Jobs\EmployeePersonalNotificationJob;
use App\Domain\Employee\Models\EmployeeDocument;
use App\Notifications\employeePersonalNotification;

class storeEmployeedocumentDeleteNotification
{
    public function execute($request)
    {
        $previousData = EmployeeDocument::where('id',$request->doc_id)->first();
        $previousInfo = Employee::find($request->employee_id);

        if($previousInfo->gender == "Male"){
            $gender = "his";
        }
        else{
            $gender = "her";
        }

        $documentName = $previousData->doc_name;

        if($previousInfo->id == Auth::user()->id){
            $message =  "$previousInfo->firstname $previousInfo->lastname deleted  $gender employee document $documentName.";
        }else{
            $userName = Auth::user()->fullname;
            $message =  "$userName deleted $previousInfo->fullname employee document $documentName.";
        }

        $showMessage = $message;
        $adminUser = Employee::role('admin')->get();

        if($previousInfo->isNotAdmin()){
            $user  = Employee::where('id',$request->employee_id)->get();
            $adminUser =  $adminUser->merge($user);
        }

        $employeeData['previousData'] = $previousInfo;        
        $employeeData['description'] = $showMessage;
        $employeeData['url'] = "/employees/$request->employee_id/docs";

        EmployeePersonalNotificationJob::dispatch($employeeData,$adminUser)->delay(Carbon::now()->addMinutes(1));
        
    }
}
