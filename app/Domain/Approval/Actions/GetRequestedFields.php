<?php


namespace App\Domain\Approval\Actions;

class GetRequestedFields
{
    /**
     * @param $approvals, $approvalId
     * @return
     */
    public function execute($approvals, $approvalId)
    {
        foreach ($approvals as $approval) {
            if ($approval->id == $approvalId) {
                return $approval->approvalRequestedFields()->get()->first();
            }
        }
    }
}
