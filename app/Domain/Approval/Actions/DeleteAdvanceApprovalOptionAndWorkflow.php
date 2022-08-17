<?php


namespace App\Domain\Approval\Actions;

use App\Domain\Approval\Models\AdvanceApprovalOption;
use App\Domain\Approval\Models\AdvanceApprovalWorkflow;

class DeleteAdvanceApprovalOptionAndWorkflow
{
    /**
     * @param $approvalId
     * @return
     */
    public function execute($approval_id)
    {
        $advanceApprovalOption = AdvanceApprovalOption::where('approval_id', $approval_id)->get();
        foreach ($advanceApprovalOption as $advanceOption) {
            AdvanceApprovalWorkflow::where('advance_approval_option_id', $advanceOption->id)->delete();
            $advanceOption->delete();
        }
    }
}
