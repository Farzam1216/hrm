<?php


namespace App\Domain\Approval\Actions;

use App\Domain\Employee\Models\Employee;
use App\Models\Notification;
use App\Notifications\DefaultApprovalNotification;
use Illuminate\Support\Facades\Session;

class SendEmailNotification
{
    /**
     * @param $requestedData
     * @param $previousInformation
     * @param $status
     * @param $comment
     * @return mixed
     */
    public function execute($requestedData, $previousInformation, $status, $comment = null)
    {
        $data = json_decode($requestedData->requested_data, true);
        $typeName = array_keys($data);
        $employee = Employee::where('id', $requestedData->requested_for_id)->first();
        $user = (new Employee)->forceFill([
            'id' => $employee->id,
            'name' => $employee->firstname,
            'email' => $employee->official_email,
        ]);
        $NotificationDetails = [
            'greetings' => 'Hello' . $employee->firstname . $employee->lastname . ',',
            'subject' => 'Information Update has been ' . $status,
            'InboxURL' => url('inbox'),
            'requester' => $employee->firstname . ' ' . $employee->lastname,
            'approval_type' => 'Information Update Request',
            'body' => 'Your Request has Been ' . $status,
            'body_information' => 'Here all previous and requested data will go,',
            'previous_information' => $previousInformation,
            'requested_information' => $data[$typeName[0]],
        ];
        if ($status == 'Approved') {
            $NotificationDetails['status'] = 'Approved';
        } else {
            $NotificationDetails['status'] = 'Denied';
        }
        if (isset($comment)) {
            $NotificationDetails['comment'] = $requestedData->comments;
        }
        $user->notify(new DefaultApprovalNotification($employee, $NotificationDetails));
        $notification = Notification::where('notifiable_id', $user['id'])->orderBy('created_at', 'desc')->first();
        $notification->is_completed = 1;
        $notification->save();
        Session::flash('success', trans('language.Employee Requested Data is Approved successfully'));
        return $status;
    }
}
