<?php

namespace App\Domain\Hiring\Actions;

use Illuminate\Support\Carbon;
use App\Jobs\HiringNotificationsJob;
use Illuminate\Support\Facades\Auth;
use App\Domain\Employee\Models\Employee;
use App\Domain\Hiring\Models\QuestionType;
use App\Notifications\hiringNotifications;
use Illuminate\Support\Facades\Notification;

class StoreQuestionTypeDeleteNotifications
{
    public function execute($id)
    {
        $data = QuestionType::where('id',$id)->first();
        $employeeInfo = Employee::find(Auth::user()->id);
        $userName = Auth::user()->fullname;
        $message =  "$userName deleted question type ($data->type).";
        $adminUser = Employee::role('admin')->get();
        $title = "Delete Question Type Notification";
        $employeeData['title'] = $title;      
        $employeeData['employeeInfo'] = $employeeInfo;        
        $employeeData['description'] = $message;
        $employeeData['url'] = "/question-types";
        HiringNotificationsJob::dispatch($employeeData,$adminUser)->delay(Carbon::now()->addMinutes(1));
        
    }
}
