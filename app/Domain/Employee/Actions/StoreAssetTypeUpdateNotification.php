<?php

namespace App\Domain\Employee\Actions;

use Illuminate\Support\Carbon;
use App\Jobs\HiringNotificationsJob;
use Illuminate\Support\Facades\Auth;
use App\Domain\Employee\Models\Employee;
use App\Domain\Employee\Models\AssetsType;
use App\Notifications\hiringNotifications;
use Illuminate\Support\Facades\Notification;

class StoreAssetTypeUpdateNotification
{
    public function execute($request,$id)
    {
        $data = $request->all();
        $employeeInfo = Employee::find(Auth::user()->id);
        $userName = Auth::user()->fullname;
        $adminUser = Employee::role('admin')->get();
        
        $differenceArray = array_diff($data,AssetsType::where('id',$id)->first()->toArray());
        $diferenceData = $differenceArray;
        $indexText =  implode(' , ',array_keys($diferenceData));

        if( $indexText == "_method , _token"){
            $changedText = substr($indexText,16);
        }
        else{
            $changedText = substr($indexText,9);
        }

        $message =  "$userName updated asset type ($request->name) ";
        $showMessage = $message.$changedText;
        $showMessage= str_replace("_method , ", "",  $showMessage);
        $showMessage= str_replace("_token , ", "",  $showMessage);
        $title = "New Employee Asset Type Notification";
        $employeeData['title'] = $title;      
        $employeeData['employeeInfo'] = $employeeInfo;        
        $employeeData['description'] = $showMessage;
        $employeeData['url'] = "/asset-types";
        if( $indexText != "_token"){
            HiringNotificationsJob::dispatch($employeeData,$adminUser)->delay(Carbon::now()->addMinutes(1));
        }
    }
}
