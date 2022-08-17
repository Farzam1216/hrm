<?php

namespace App\Domain\Employee\Actions;

use Illuminate\Support\Carbon;
use App\Jobs\HiringNotificationsJob;
use Illuminate\Support\Facades\Auth;
use App\Domain\Employee\Models\Employee;
use App\Domain\Employee\Models\VisaType;
use App\Notifications\hiringNotifications;
use Illuminate\Support\Facades\Notification;

class StoreVisaTypeUpdateNotifications
{
    public function execute($request,$id)
    {
        $data = $request->all();
        $employeeInfo = Employee::find(Auth::user()->id);
        $userName = Auth::user()->fullname;
        $adminUser = Employee::role('admin')->get();
        $differenceArray = array_diff($data,VisaType::where('id',$id)->first()->toArray());
        $diferenceData = $differenceArray;
        $indexText =  implode(' , ',array_keys($diferenceData));
        
        if( $indexText == "_method , _token"){
            $changedText = substr($indexText,16);
        }
        else{
            $changedText = substr($indexText,9);
        }

        $message =  "$userName updated visa type ($request->visa_type) ";
        $showMessage = $message.$changedText;
        $showMessage= str_replace("_method , ", "",  $showMessage);
        $showMessage= str_replace("_token , ", "",  $showMessage);
        $showMessage= str_replace("visa_type , ", "visa type , ",  $showMessage);
        $title = "New Employee Visa Type Notification";
        $employeeData['title'] = $title;      
        $employeeData['employeeInfo'] = $employeeInfo;        
        $employeeData['description'] = $showMessage;
        $employeeData['url'] = "/visa-types";

        if( $indexText != "_method , _token"){
            HiringNotificationsJob::dispatch($employeeData,$adminUser)->delay(Carbon::now()->addMinutes(1));
        }
        
    }
}
