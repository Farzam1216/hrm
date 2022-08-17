<?php


namespace App\Domain\Approval\Actions;

use App\Domain\Approval\Models\Approval;

class RestoreEmployementStatusApproval
{
    /**
     * @param $approvalId
     * @return bool
     */
    public function execute($approvalId)
    {
        Approval::where('id', $approvalId)->update([
            'name' => 'Employment status',
            'description' => 'Use this approval for requesting an employment status change for an employee. The approval form contains all fields from the Employment Status table. Additional fields may also be added.',
        ]);
        $restoreDefaultFields = new RestoreDefaultFields();
        $restoreDefaultFields->execute($approvalId);

        return true;
    }
}
