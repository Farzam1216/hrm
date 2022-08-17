<?php

namespace App\Domain\Hiring\Actions;

use Illuminate\Support\Carbon;
use App\Jobs\HiringNotificationsJob;
use Illuminate\Support\Facades\Auth;
use App\Domain\Employee\Models\Employee;
use App\Domain\Hiring\Models\QuestionType;
use App\Notifications\hiringNotifications;
use Illuminate\Support\Facades\Notification;

class StoreQuestionTpyeUpdateNotifications
{
    public function execute($request,$id)
    {
        $data = $request->all();
        $differenceArray = array_diff($data,QuestionType::where('id',$id)->first()->toArray());
        $diferenceData = $differenceArray;
        $indexText =  implode(' , ',array_keys($diferenceData));
        $employeeInfo = Employee::find(Auth::user()->id);
        $userName = Auth::user()->fullname;
        $message =  "$userName updated question type ($request->question_type).";
        $adminUser = Employee::role('admin')->get();
        $title = "Update Question Type Notification";
        $employeeData['title'] = $title;      
        $employeeData['employeeInfo'] = $employeeInfo;        
        $employeeData['description'] = $message;
        $employeeData['url'] = "/question-types";
        if( $indexText != "_method , _token"){
            HiringNotificationsJob::dispatch($employeeData,$adminUser)->delay(Carbon::now()->addMinutes(1));
         }
    }
}
