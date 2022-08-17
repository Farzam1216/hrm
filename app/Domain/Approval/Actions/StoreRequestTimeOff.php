<?php


namespace App\Domain\Approval\Actions;

use Session;
use Illuminate\Support\Carbon;
use App\Jobs\RequestTimeOffJob;
use Illuminate\Support\Facades\Auth;
use App\Domain\TimeOff\Models\RequestTimeOff;
use App\Domain\TimeOff\Models\AssignTimeOffType;
use App\Domain\Approval\Actions\SendTimeOffNotification;
use App\Domain\Approval\Actions\GetRequestTimeoffDetails;
use App\Domain\Approval\Actions\StoreApprovalRequestedDataForTimeOff;
use App\Domain\Approval\Actions\ApproveTimeOffRequest;

class StoreRequestTimeOff
{
    /**
     * @param $data
     */
    public function execute($data, $id)
    {
        $requestTimeoff = new RequestTimeOff();
        $requestTimeoff->assign_timeoff_type_id = $data['assign_timeoff_type_id'];
        $requestTimeoff->to = $data['to'];
        $requestTimeoff->from = $data['from'];
        $requestTimeoff->note = $data['note'];
        if (isset($data['note'])) {
            $requestTimeoff->note = $data['note'];
        } else {
            $requestTimeoff->note = "None";
        }
        $requestTimeoff->status = 'pending';
        $requestTimeoff->employee_id = $id;
        $requestTimeoff->save();
        
        $assignTimeOffType = AssignTimeOffType::where('id', $data['assign_timeoff_type_id'])->with('timeOffType')->first();
        $getRequestTimeOff = new GetRequestTimeoffDetails();
        $requestTimeoffHoursSum = $getRequestTimeOff->execute($data, $requestTimeoff->id);
        $storeRequestTimeOff = new StoreApprovalRequestedDataForTimeOff();
        $approvalRequestedData = $storeRequestTimeOff->execute($requestTimeoff, $requestTimeoffHoursSum, $assignTimeOffType->timeOffType, $id);

        if ($data['permissionCheck'] == 'true') {
            $approvalData = [
                'requesttimeoffid' => $requestTimeoff->id,
                'commentedById' => Auth::id(),
                'comment' => ''
            ];

            $approvetimeOff = new ApproveTimeOffRequest();
            $approvetimeOff->execute($approvalData);

            Session::flash('success', trans('language.Time off request is submitted and approved successfully'));
        }

        if ($data['permissionCheck'] == 'false') {
            $notification = new SendTimeOffNotification();
            $notification->execute($requestTimeoff, $data, $requestTimeoffHoursSum, $assignTimeOffType->timeOffType, $id);

            Session::flash('success', trans('language.Time off request is submitted successfully'));
        }

        return $requestTimeoff;
    }
}
