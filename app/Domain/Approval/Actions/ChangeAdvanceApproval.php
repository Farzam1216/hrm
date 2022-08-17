<?php


namespace App\Domain\Approval\Actions;

use App\Domain\Approval\Models\AdvanceApprovalOption;

class ChangeAdvanceApproval
{
    /**
     * @param $approvalId,$data
     * @return bool
     */
    public function execute($data, $approval_id)
    {
        //delete all options first
        (new RemoveAdvanceApproval)->execute($approval_id);
        //create a new approval option
        AdvanceApprovalOption::create([
            'approval_id' => $approval_id,
            'advance_approval_type' => $data['advance_approval_type'],
        ]);
    }
}
