<?php


namespace App\Domain\Approval\Actions;

use App\Domain\Approval\Models\Approval;

class RestoreCompensationApproval
{
    /**
     * @param $approvalId
     * @return bool
     */
    public function execute($approvalId)
    {
        Approval::where('id', $approvalId)->update([
            'name' => 'Compensation',
            'description' => 'Use this approval for requesting changes to an employee\'s compensation. The request will contain all fields in the compensation table. Additional fields may be added to the request.',
        ]);
        $restoreDefaultFields = new RestoreDefaultFields();
        $restoreDefaultFields->execute($approvalId);

        return true;
    }
}
