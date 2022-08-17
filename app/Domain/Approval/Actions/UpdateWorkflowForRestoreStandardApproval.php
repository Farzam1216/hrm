<?php


namespace App\Domain\Approval\Actions;

use App\Domain\Approval\Models\ApprovalWorkflow;

class UpdateWorkflowForRestoreStandardApproval
{
    /**
    * @param $approvalId
    */
    public function execute($approvalId)
    {
        //first delete old hierarchy
        $approvalWorkflows = ApprovalWorkflow::where('approval_id', $approvalId)->get();
        foreach ($approvalWorkflows as $workflows) {
            $workflows->delete();
        }
        //add default standard approval
        $approvalHierarchy = ['FullAdmin' => 'none'];
        ApprovalWorkflow::create([
            'approval_id' => $approvalId,
            'approval_hierarchy' => json_encode($approvalHierarchy),
            'level_number' => 1,
        ]);
    }
}
