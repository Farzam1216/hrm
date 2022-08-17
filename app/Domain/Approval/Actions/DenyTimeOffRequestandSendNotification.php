<?php


namespace App\Domain\Approval\Actions;

use Illuminate\Support\Carbon;
use App\Jobs\DenyTimeOffJob;
use App\Traits\SendNotification;
use Illuminate\Support\Facades\Auth;
use App\Domain\Employee\Models\Employee;
use App\Domain\TimeOff\Models\RequestTimeOff;
use App\Notifications\DefaultApprovalNotification;
use App\Domain\Approval\Models\ApprovalRequestedDataField;
use App\Domain\Approval\Actions\SetNotificationStatusCompleted;

class DenyTimeOffRequestandSendNotification
{
    use SendNotification;
    /**
     * @param $data
     */
    public function execute($data)
    {
        $requestedDataField = ApprovalRequestedDataField::whereJsonContains('requested_data->requesttimeoff_id', (int) $data['id'])->first();
        $requestedData = json_decode($requestedDataField->requested_data, true);
        $requestTimeoff = RequestTimeOff::find($data['id']);
        $requestedDataField->update([ 'comments' => $data['comment'], 'is_approved'=>false, 'approved_by'=> Auth::id() ]);
        $requestTimeoff->status = 'Denied';
        $requestTimeoff->save();
        $requesterEmployee = Employee::where('id', $requestedDataField->requested_by_id)->first();
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
                ' from '.$requestTimeoff->to. ' - '.$requestTimeoff->from.' has been denied.',
            'body' => $requesterEmployee->firstname.', your time off request for '.$requestedData['total_hours'].' hours of '.$requestedData['timeofftype_name'].
                ' from '.$requestTimeoff->to. ' - '.$requestTimeoff->from.' has been denied.',
            'body_information' => Auth::user()->getFullNameAttribute().' denied time off request for '.$requesterEmployee->getFullNameAttribute(),
            'comment' => $requestedDataField->comments,
        ];
        DenyTimeOffJob::dispatch($requesterEmployee, $notificationDetail)->delay(Carbon::now()->addMinutes(1));
        if (isset($data['notificationId'])) {
            $notificationIsCompleted =new SetNotificationStatusCompleted();
            $notificationIsCompleted->execute($data['notificationId']);
        }
    }
}
