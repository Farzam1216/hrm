<?php

namespace App\Domain\Employee\Actions;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Domain\Employee\Models\Asset;
use App\Domain\Employee\Models\Employee;
use Illuminate\Support\Facades\Notification;
use App\Jobs\EmployeePersonalNotificationJob;
use App\Notifications\employeePersonalNotification;

class storeEmployeeAssetDeleteNotification
{
    public function execute($request,$id)
    {
        $previousInfo = Employee::find($request->employee_id);
        $asset = Asset::find($id);

        if($previousInfo->gender == "Male"){
            $gender = "his";
        }
        else{
            $gender = "her";
        }

        $assetSerial = $asset->serial;

        if($previousInfo->id == Auth::user()->id){
            $message =  "$previousInfo->firstname$previousInfo->lastname deleted $gender assets details ($assetSerial).";
        }else{
            $userName = Auth::user()->fullname;
            $message =  "$userName deleted $previousInfo->fullname assets details ($assetSerial).";
        }

        $showMessage = $message;
        $adminUser = Employee::role('admin')->get();
        $hrUser = Employee::role('Hr Manager')->get();
        $users =  $adminUser->merge($hrUser);

        if($previousInfo->isNotAdmin()){
            $user  = Employee::where('id',$request->employee_id)->get();
            $users =  $users->merge($user);
        }
        
        $employeeData['previousData'] = $previousInfo;        
        $employeeData['description'] = $showMessage;
        $employeeData['url'] = "/employees/$request->employee_id/assets";
        EmployeePersonalNotificationJob::dispatch($employeeData,$users)->delay(Carbon::now()->addMinutes(1));
    }
}
