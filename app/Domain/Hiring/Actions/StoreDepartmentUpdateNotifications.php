<?php

namespace App\Domain\Hiring\Actions;

use Illuminate\Support\Carbon;
use App\Jobs\HiringNotificationsJob;
use Illuminate\Support\Facades\Auth;
use App\Domain\Employee\Models\Employee;
use App\Domain\Employee\Models\Department;
use App\Notifications\hiringNotifications;
use Illuminate\Support\Facades\Notification;

class StoreDepartmentUpdateNotifications
{
    public function execute($request,$id)
    {
        $data = $request->all();
        $employeeInfo = Employee::find(Auth::user()->id);
        $userName = Auth::user()->fullname;
        $adminUser = Employee::role('admin')->get();
        $hrUser = Employee::role('Hr Manager')->get();
        $users =  $adminUser->merge($hrUser);
        $differenceArray = array_diff($data,Department::where('id',$id)->first()->toArray());
        $diferenceData = $differenceArray;
        $indexText =  implode(' , ',array_keys($diferenceData));
        if( $indexText == "_method , _token"){
            $changedText = substr($indexText,16);
        }
        else{
            $changedText = substr($indexText,9);
        }
        $message =  "$userName updated job department ($request->department_name) ";
        $showMessage = $message.$changedText;
        $showMessage= str_replace("_method , ", "",  $showMessage);
        $showMessage= str_replace("_token , ", "",  $showMessage);
        $showMessage= str_replace("department_name", "department name",  $showMessage);
        $title = "Update Department Notification";
        $employeeData['title'] = $title;      
        $employeeData['employeeInfo'] = $employeeInfo;        
        $employeeData['description'] = $showMessage;
        $employeeData['url'] = "/departments";
        if( $indexText != "_method , _token"){
            HiringNotificationsJob::dispatch($employeeData,$users)->delay(Carbon::now()->addMinutes(1));
        }
    }
}
