<?php


namespace App\Domain\Approval\Actions;

use App\Domain\Approval\Models\Approval;

class GetDefaultFields
{
    /**
     * @param $approvalId
     * @return mixed $fields
     */
    public function execute($approvalId)
    {
        $approval = Approval::with('approvalRequestedFields')->where('id', $approvalId)->first();
        $fields = [];
        foreach ($approval->approvalRequestedFields as $requestedFields) {
            $fields = json_decode($requestedFields->form_fields, true);
        }

        return $fields;
    }
}
