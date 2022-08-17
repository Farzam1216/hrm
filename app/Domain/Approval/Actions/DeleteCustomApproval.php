<?php


namespace App\Domain\Approval\Actions;

use App\Domain\Approval\Models\Approval;

class DeleteCustomApproval
{
    /**
     * @param $approvalId
     */
    public function execute($approvalId)
    {
        Approval::find($approvalId)->delete();
    }
}
