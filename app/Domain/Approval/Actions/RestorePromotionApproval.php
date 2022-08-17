<?php


namespace App\Domain\Approval\Actions;

use App\Domain\Approval\Models\Approval;

class RestorePromotionApproval
{
    /**
     * @param $approvalId
     * @return bool
     */
    public function execute($approvalId)
    {
        Approval::where('id', $approvalId)->update([
            'name' => 'Promotion',
            'description' => 'Use this approval for requesting a promotion for an employee. The approval form includes all fields from the Compensation table and the Job Information table. This workflow enables you to request a promotion without having to send two separate requests, one for Compensation and one for Job Information. Additional fields may also be added.',
        ]);
        $restoreDefaultFields = new RestoreDefaultFields();
        $restoreDefaultFields->execute($approvalId);

        return true;
    }
}
