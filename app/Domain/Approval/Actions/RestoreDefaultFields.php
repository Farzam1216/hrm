<?php


namespace App\Domain\Approval\Actions;

use App\Domain\Approval\Models\ApprovalRequestedField;

class RestoreDefaultFields
{
    /**
     * @param $approvalId
     */
    public function execute($approvalId)
    {
        $approvalFields = ApprovalRequestedField::where('approval_id', $approvalId)->pluck('form_fields')->first();
        $approvalFields_array = json_decode($approvalFields, 'true');
        //group default fields only
        foreach ($approvalFields_array as $type => $fields) {
            if ($type == 'Default') {
                $defaultFields = [$type => $fields];
            }
        }
        ApprovalRequestedField::where('approval_id', $approvalId)->update([
            'form_fields' => json_encode($defaultFields),
        ]);
    }
}
