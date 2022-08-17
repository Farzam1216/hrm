<?php

namespace App\Domain\Employee\Actions;

use Illuminate\Support\Carbon;
use App\Models\Company\Document;
use App\Jobs\HiringNotificationsJob;
use Illuminate\Support\Facades\Auth;
use App\Domain\Employee\Models\Employee;
use App\Notifications\hiringNotifications;
use Illuminate\Support\Facades\Notification;

class StoreDocumentDeleteNotification
{
    public function execute($id)
    {
        $data = Document::find($id);
        $employeeInfo = Employee::find(Auth::user()->id);
        $userName = Auth::user()->fullname;
        $message =  "$userName deleted company document ($data->name).";
        $adminUser = Employee::role('admin')->get();
        $title = "New Company Document Notification";
        $employeeData['title'] = $title;      
        $employeeData['employeeInfo'] = $employeeInfo;        
        $employeeData['description'] = $message;
        $employeeData['url'] = "/documents";
        HiringNotificationsJob::dispatch($employeeData,$adminUser)->delay(Carbon::now()->addMinutes(1));
    }
}
