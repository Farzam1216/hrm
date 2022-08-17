<?php


namespace App\Domain\Approval\Actions;

use App\Domain\Approval\Models\Approval;
use App\Domain\Approval\Models\ApprovalRequestedField;
use App\Domain\Approval\Models\ApprovalRequester;
use App\Domain\Approval\Models\ApprovalWorkflow;
use Illuminate\Support\Facades\DB;

class StoreApprovalWithRequestedFields
{
    /**     *
     * @param mixed $groupedData     *
     */
    public function execute($groupedData)
    {
        $categories = [];
        //No Default group means, no fields for comments is available in the field list
        if (!array_key_exists('Default', $groupedData)) {
            $commentsField = DB::table('approval_form_fields')->where(['field_name' => 'comments', 'model' => null])->first();
            if (isset($commentsField)) {
                $groupedData[$commentsField->group] = [
                    $commentsField->field_name => [
                        'name' => $commentsField->name,
                        'type' => $commentsField->type,
                        'status' => $commentsField->status,
                    ],
                ];
            }
        }
        $requestedFields = [];
        foreach ($groupedData as $name => $group) {
            if ($name != 'Approval') {
                $requestedFields[$name] = $group;
            }
        }
        $approval = Approval::create([
            'approval_type_id' => 3,
            'name' => $groupedData['Approval']['name'],
            'description' => $groupedData['Approval']['description'],
            'status' => 1,
        ]);

        ApprovalRequestedField::create([
            'approval_id' => $approval->id,
            'form_fields' => json_encode($requestedFields),
        ]);

        $approvalHierarchy = ['FullAdmin' => 'none'];
        ApprovalWorkflow::create([
            'approval_id' => $approval->id,
            'approval_hierarchy' => json_encode($approvalHierarchy),
            'level_number' => 1,
        ]);

        $approvalRequesters = ['Manager' => 'none'];
        ApprovalRequester::create([
            'approval_id' => $approval->id,
            'approval_requester_data' => json_encode($approvalRequesters),
        ]);
    }
}
