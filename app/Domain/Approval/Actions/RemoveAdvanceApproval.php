<?php


namespace App\Domain\Approval\Actions;

use App\Domain\Approval\Models\ApprovalRequester;
use App\Domain\Approval\Models\ApprovalWorkflow;

class RemoveAdvanceApproval
{
    /**
     * @param $approvalId
     * @return bool
     */
    public function execute($approvalId)
    {
        (new DeleteAdvanceApprovalOptionAndWorkflow())->execute($approvalId);
        ApprovalRequester::where('approval_id', $approvalId)->whereNotNull('advance_approval_option_id')->delete();
        ApprovalWorkflow::where('approval_id', $approvalId)->whereNotNull('advance_approval_option_id')->delete();
    }
}
