<?php


namespace App\Domain\Approval\Actions;

use App\Domain\Approval\Models\Approval;

class RestoreJobInformationApproval
{
    /**
     * @param $approvalId
     * @return bool
     */
    public function execute($approvalId)
    {
        Approval::where('id', $approvalId)->update([
            'name' => 'Job Information',
            'description' => 'Use this approval for requesting changes to an employee\'s Job Information. The approval form includes all fields from the job information table. Additional fields may also be added.',
        ]);
        $restoreDefaultFields = new RestoreDefaultFields();
        $restoreDefaultFields->execute($approvalId);

        return true;
    }
}
