<?php

namespace App\Domain\Hiring\Actions;

use Illuminate\Support\Carbon;
use App\Jobs\HiringNotificationsJob;
use Illuminate\Support\Facades\Auth;
use App\Domain\Employee\Models\Employee;
use App\Domain\Employee\Models\Location;
use App\Notifications\hiringNotifications;
use Illuminate\Support\Facades\Notification;

class StoreLocationUpdateNotifications
{
    public function execute($request,$id)
    {
        $data = $request->all();
        $employeeInfo = Employee::find(Auth::user()->id);
        $userName = Auth::user()->fullname;
        $adminUser = Employee::role('admin')->get();
        $hrUser = Employee::role('Hr Manager')->get();
        $users =  $adminUser->merge($hrUser);
        $differenceArray = array_diff($data,Location::where('id',$id)->first()->toArray());
        $diferenceData = $differenceArray;
        $indexText =  implode(' , ',array_keys($diferenceData));
        if( $indexText == "_token , _method"){
            $changedText = substr($indexText,16);
        }
        else{
            $changedText = substr($indexText,9);
        }
        $message =  "$userName updated job location ($request->name) ";
        $showMessage = $message.$changedText;
        $showMessage= str_replace("_method , ", "",  $showMessage);
        $showMessage= str_replace("_token , ", "",  $showMessage);
        $showMessage= str_replace("name", "name",  $showMessage);
        $showMessage= str_replace("city", "city",  $showMessage);
        $showMessage= str_replace("state", "state",  $showMessage);
        $showMessage= str_replace("phone_number", "phone number",  $showMessage);
        $showMessage= str_replace("street_1", "street 1",  $showMessage);
        $showMessage= str_replace("street_2", "street 2",  $showMessage);
        $showMessage= str_replace("zip_code", "zip code",  $showMessage);
        $title = "Update Location Notification";
        $employeeData['title'] = $title;      
        $employeeData['employeeInfo'] = $employeeInfo;        
        $employeeData['description'] = $showMessage;
        $employeeData['url'] = "/locations";
        if( $indexText != "_method , _token"){
            HiringNotificationsJob::dispatch($employeeData,$users)->delay(Carbon::now()->addMinutes(1));
        }
    }
}
