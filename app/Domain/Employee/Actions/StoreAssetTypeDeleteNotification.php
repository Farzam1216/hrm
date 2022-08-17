<?php

namespace App\Domain\Employee\Actions;

use Illuminate\Support\Carbon;
use App\Jobs\HiringNotificationsJob;
use Illuminate\Support\Facades\Auth;
use App\Domain\Employee\Models\Employee;
use App\Domain\Employee\Models\AssetsType;
use App\Notifications\hiringNotifications;
use Illuminate\Support\Facades\Notification;

class StoreAssetTypeDeleteNotification
{
    public function execute($id)
    {
        $data = AssetsType::find($id);
        $employeeInfo = Employee::find(Auth::user()->id);
        $userName = Auth::user()->fullname;
        $message =  "$userName deleted asset type ($data->name).";
        $adminUser = Employee::role('admin')->get();
        $title = "New Employee Asset Type Notification";
        $employeeData['title'] = $title;      
        $employeeData['employeeInfo'] = $employeeInfo;        
        $employeeData['description'] = $message;
        $employeeData['url'] = "/asset-types";
        HiringNotificationsJob::dispatch($employeeData,$adminUser)->delay(Carbon::now()->addMinutes(1));
    }
}
