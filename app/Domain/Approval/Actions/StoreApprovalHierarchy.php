<?php


namespace App\Domain\Approval\Actions;

use App\Domain\Approval\Models\ApprovalWorkflow;

class StoreApprovalHierarchy
{
    /**
     * @param $hierarchyData, $levelNumber, $data
     * @return
     */
    public function execute($hierarchyData, $levelNumber, $data, &$flag)
    {
        $approvalID = $data['approvalId'];
        if (isset($data['advanceApprovalOptionId']) || isset($data['approvalPath'])) {
            (new StoreAdvanceApprovalHierarchy())->execute($hierarchyData, $levelNumber, $data, $flag);
        } else {
            //simple approval worklow
            ApprovalWorkflow::updateOrCreate(
                ['level_number' => $levelNumber, 'approval_id' => $approvalID, 'advance_approval_option_id' => null],
                ['approval_hierarchy' => json_encode($hierarchyData)]
            );
        }
    }
}
