<?php


namespace App\Domain\Approval\Actions;

use App\Domain\Approval\Models\Approval;

class GetApproval
{
    /**
     * @param $approvalId
     * @return mixed
     */
    public function execute($approvalId)
    {
        return Approval::where('id', $approvalId)->get()->first();
    }
}
