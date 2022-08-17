<?php


namespace App\Domain\Approval\Actions;

use App\Domain\Approval\Models\Approval;
use App\Domain\Approval\Models\ApprovalRequestedDataField;
use App\Domain\Approval\Models\ApprovalWorkflow;
use App\Models\Notification;
use App\Traits\SendNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;

class RequestStatusChange
{
    use SendNotification;
    /**
     * @param $requestedDataID
     * @param $status
     * @param $data
     */
    public function execute($requestedDataID, $status, $data)
    {
        $notification = Notification::where('id', $data['notificationId'])->first();
        $notification->is_completed = 1;
        $notificationData = json_decode($notification->data, true);
        if ($status == 'true') {
            $notificationData['status'] = 'Approved';
        } else {
            $notificationData['status'] = 'Denied';
        }
        $notification->data = $notificationData;
        $notification->save();
        $approvalRequest = ApprovalRequestedDataField::find($requestedDataID);
        $approvalRequest->comments = $data['comment'];
        $approvalWorkflow = ApprovalWorkflow::findOrFail($approvalRequest->approval_workflow_id);
        $approvalRequest->save();
        if ($approvalWorkflow->created_at->equalTo($approvalWorkflow->updated_at) || $approvalRequest->updated_at->greaterThan($approvalWorkflow->updated_at)) {
            if ($approvalWorkflow->advance_approval_option_id == null) {
                $hierarchyLevels = ApprovalWorkflow::where(
                    'approval_id',
                    $approvalWorkflow->approval_id
                )->orderBy('level_number')->get();
            } else {
                $hierarchyLevels = ApprovalWorkflow::where('advance_approval_option_id', $approvalWorkflow->advance_approval_option_id)->get();
            }
        } else {
            $hierarchyLevels = DB::table('approval_workflow_histories')->where(
                'approval_id',
                $approvalRequest->approval_id
            )->whereDate('created_at', '>=', $approvalWorkflow->updated_at)->get();
        }
        if ($status == "true") {
            $approvalRequest->is_approved = 1;
            $approvalRequest->approved_by = Auth::user()->id;
            if ($approvalRequest->save()) {
                $currentLevel = null;
                foreach ($hierarchyLevels as $hierarchyLevel) {
                    if ($hierarchyLevel->id == $approvalRequest->approval_workflow_id && $hierarchyLevels->count() != $hierarchyLevel->level_number) {
                        $currentLevel = $hierarchyLevel;
                        continue;
                    } elseif ($hierarchyLevel->id == $approvalRequest->approval_workflow_id && $hierarchyLevels->count() == $hierarchyLevel->level_number) {
                        $approvalType = Approval::with('approvalType')->where('id', $approvalRequest->approval_id)->first();
                        if ($approvalType->approvalType->name == 'Standard' || $approvalType->approvalType->name == 'Custom') {
                            (new AddStandardOrCustomApprovalType())->execute($approvalRequest);
                        } elseif ($approvalType->approvalType->name == 'Fixed') {
                            if ($approvalRequest->approval_id == 1) {
                                (new UpdateInformationAfterApproval())->execute($approvalRequest);
                            }
                        }
                    }
                    if (isset($currentLevel) && $currentLevel->level_number < $hierarchyLevel->level_number) {
                        $newRequest = new ApprovalRequestedDataField();
                        $newRequest->requested_field_id = $approvalRequest->requested_field_id;
                        $newRequest->approval_id = $approvalRequest->approval_id;
                        $newRequest->requested_by_id = $approvalRequest->requested_by_id;
                        $newRequest->requested_for_id = $approvalRequest->requested_for_id;
                        $newRequest->requested_data = $approvalRequest->requested_data;
                        $newRequest->approval_workflow_id = $hierarchyLevel->id;
                        $newRequest->comments = $approvalRequest->comments;
                        $newRequest->is_approved = 1;
                        $newRequest->approved_by = Auth::user()->id;
                        if ($newRequest->save()) {
                            $approvalRequest->delete();
                            $currentLevel = null;
                            // $notification
                            $notificationData['ApproveURL'] = '/approval-requests/' . $newRequest->id . '/approve/true';
                            $notificationData['DisapproveURL'] = '/approval-requests/' . $newRequest->id . '/approve/false';
                            $notificationData['status'] = 'Pending';
                            $this->sendEmailAndNotification(
                                $approvalRequest->requested_for_id,
                                $hierarchyLevel->id,
                                $notificationData
                            );
                            Session::flash(
                                'success',
                                trans('language.Employee Requested Data is Approved successfully')
                            );
                            break;
                        }
                    }
                }
            }
        } else {
            //disapprove
            $approvalRequest->is_approved = 0;
            $approvalRequest->approved_by = Auth::user()->id;
            $approvalRequest->save();
            (new SendEmailNotification())->execute($approvalRequest, $notificationData['previous_information'], 'Denied', $approvalRequest->comments);
            Session::flash('success', 'language.Employee Requested Data is Denied successfully');
            //As the data have been stored in history table after update, so there is no need to keep this data in original table.
        }
    }
}
