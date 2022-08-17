<?php


namespace App\Domain\Approval\Actions;

use Illuminate\Support\Carbon;
use App\Jobs\ApproveTimeOffJob;
use Illuminate\Support\Facades\Auth;
use App\Domain\Employee\Models\Employee;
use App\Domain\TimeOff\Models\RequestTimeOff;
use App\Notifications\DefaultApprovalNotification;

class SendApprovedTimeOffNotificationToEmployee
{
    /**
     * @param $requesterEmployee
     * @param $requestedDataField
     * @param $requesttimeoffid
     */
    public function execute($requesterEmployee, $requestedDataField, $requesttimeoffid)
    {
        $user = (new Employee)->forceFill([
            'id' => $requesterEmployee->id, 'name' => $requesterEmployee->firstname, 'email' => $requesterEmployee->official_email,
        ]);
        $requestedData = json_decode($requestedDataField->requested_data, true);
        $requestTimeoff = RequestTimeOff::find($requesttimeoffid);
        $notificationDetail = [
            'greetings' => 'Hello,',
            'employeeInfo' => $requesterEmployee,
            'subject' => 'Your time off request has a response',
            'title' => 'Your time off request has a response',
            'InboxURL' => url('en/inbox'),
            'url' => '/employee/' . $requesterEmployee->id . '/timeoff',
            'requester' => $requesterEmployee->getFullNameAttribute(),
            'approval_type' => 'Time Off Request',
            'message' => $requesterEmployee->firstname.', your time off request for '.$requestedData['total_hours'].' hours of '.$requestedData['timeofftype_name'].
                ' from '.$requestTimeoff->to. ' - '.$requestTimeoff->from.' has been approved by '.Auth::user()->getFullNameAttribute(),
            'body' => $requesterEmployee->firstname.', your time off request for '.$requestedData['total_hours'].' hours of '.$requestedData['timeofftype_name'].
                ' from '.$requestTimeoff->to. ' - '.$requestTimeoff->from.' has been approved by '.Auth::user()->getFullNameAttribute(),
            'body_information' => Auth::user()->getFullNameAttribute().' approved time off request for '.$requesterEmployee->getFullNameAttribute(),
            'comment' => $requestedDataField->comments,
        ];
        ApproveTimeOffJob::dispatch($requesterEmployee, $notificationDetail)->delay(Carbon::now()->addMinutes(1));
    }
}
