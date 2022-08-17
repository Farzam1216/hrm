<?php

namespace App\Domain\Employee\Actions;

use Illuminate\Support\Carbon;
use App\Jobs\HiringNotificationsJob;
use Illuminate\Support\Facades\Auth;
use App\Domain\Employee\Models\Employee;
use App\Notifications\hiringNotifications;
use App\Domain\Employee\Models\DocumentType;
use Illuminate\Support\Facades\Notification;

class StoreDocumentTypesDeleteNotifications
{
    public function execute($id)
    {
        $data = DocumentType::find($id);
        $employeeInfo = Employee::find(Auth::user()->id);
        $userName = Auth::user()->fullname;
        $message =  "$userName deleted company document type ($data->doc_type_name).";
        $adminUser = Employee::role('admin')->get();
        $title = "New Company Document Types Notification";
        $employeeData['title'] = $title;      
        $employeeData['employeeInfo'] = $employeeInfo;        
        $employeeData['description'] = $message;
        $employeeData['url'] = "/doc-types";
        HiringNotificationsJob::dispatch($employeeData,$adminUser)->delay(Carbon::now()->addMinutes(1));
    }
}
