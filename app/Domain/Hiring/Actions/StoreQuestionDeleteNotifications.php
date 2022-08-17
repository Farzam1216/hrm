<?php

namespace App\Domain\Hiring\Actions;

use Illuminate\Support\Carbon;
use App\Jobs\HiringNotificationsJob;
use Illuminate\Support\Facades\Auth;
use App\Domain\Hiring\Models\Question;
use App\Domain\Employee\Models\Employee;
use App\Notifications\hiringNotifications;
use Illuminate\Support\Facades\Notification;

class StoreQuestionDeleteNotifications
{
    public function execute($id)
    {
        $data = Question::find($id);
        $employeeInfo = Employee::find(Auth::user()->id);
        $userName = Auth::user()->fullname;
        $message =  "$userName deleted canned question ($data->question).";
        $adminUser = Employee::role('admin')->get();
        $title = "Delete Question Notification";
        $employeeData['title'] = $title;      
        $employeeData['employeeInfo'] = $employeeInfo;        
        $employeeData['description'] = $message;
        $employeeData['url'] = "/questions";
        HiringNotificationsJob::dispatch($employeeData,$adminUser)->delay(Carbon::now()->addMinutes(1));
    }
}
