<?php


namespace App\Domain\Approval\Actions;

use App\Domain\Approval\Models\ApprovalRequester;

class RestoreStandardApproval
{
    /**
     * @param $data
     *
     * @return bool
     */
    public function execute($data)
    {
        $approvalId = $data->route('id');

        $updateWorkflowForRestoreStandardApproval = new UpdateWorkflowForRestoreStandardApproval();
        $updateWorkflowForRestoreStandardApproval->execute($approvalId);
        //update 'who can make request' to default
        ApprovalRequester::where('approval_id', $approvalId)->update(
            ['approval_requester_data' => json_encode(['Manager' => 'none'])]
        );
        //standard approval
        if ($approvalId == 3) {
            $restoreCompensationApproval = new RestoreCompensationApproval();
            $restoreCompensationApproval->execute($approvalId);
        }
        if ($approvalId == 4) {
            $restoreEmployementStatusApproval = new RestoreEmployementStatusApproval();
            $restoreEmployementStatusApproval->execute($approvalId);
        }
        if ($approvalId == 5) {
            $restoreJobInformationApproval = new RestoreJobInformationApproval();
            $restoreJobInformationApproval->execute($approvalId);
        }
        if ($approvalId == 6) {
            $restorePromotionApproval = new RestorePromotionApproval();
            $restorePromotionApproval->execute($approvalId);
        }

        return false;
    }
}
