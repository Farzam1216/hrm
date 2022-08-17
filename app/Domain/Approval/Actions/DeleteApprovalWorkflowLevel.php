<?php


namespace App\Domain\Approval\Actions;

use App\Domain\Approval\Models\Approval;
use App\Domain\Approval\Models\ApprovalWorkflow;

class DeleteApprovalWorkflowLevel
{
    /**
     * @param $workflowId
     */
    public function execute($workflowId)
    {
        $approvalWorkflow = ApprovalWorkflow::find($workflowId);
        $approvalId = $approvalWorkflow->approval_id;
        $approvalWorkflow->delete();
        //update level numbers for approval workflow
        if (isset($approvalWorkflow->advance_approval_option_id)) {
            $approvalWorkflows = ApprovalWorkflow::where([
                'approval_id' => $approvalId,
                'advance_approval_option_id' => $approvalWorkflow->advance_approval_option_id
            ])->get();
        } else {
            $approvalWorkflows = ApprovalWorkflow::whereNull('advance_approval_option_id')->where('approval_id', $approvalId)->get();
        }
        $sortedWorkflows = $approvalWorkflows->sortBy('level_number');
        foreach ($sortedWorkflows as $key => $workflow) {
            $workflow->update(['level_number' => ++$key]);
        }
    }
}
