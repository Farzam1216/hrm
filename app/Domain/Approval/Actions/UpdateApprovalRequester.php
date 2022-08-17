<?php


namespace App\Domain\Approval\Actions;

use App\Domain\Approval\Models\ApprovalRequester;

class UpdateApprovalRequester
{
    /**
     * @param $data
     * @return
     */
    public function execute($data)
    {
        ApprovalRequester::where('approval_id', $data['approvalId'])->whereNull('advance_approval_option_id')->update([
            'approval_requester_data' => json_encode($data['makeTypeofRequest']),
        ]);
    }
}
