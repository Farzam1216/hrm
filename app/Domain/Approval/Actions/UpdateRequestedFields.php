<?php


namespace App\Domain\Approval\Actions;

use App\Domain\Approval\Models\Approval;
use App\Domain\Approval\Models\ApprovalRequestedField;
use Illuminate\Support\Facades\DB;

class UpdateRequestedFields
{
    /**
     * @param $id
     * @param $groupedData
     * @return mixed
     */
    public function execute($id, $groupedData)
    {
        $getDefaultFields = new GetDefaultFields();
        $requestedFields = $getDefaultFields->execute($id);
        if (isset($requestedFields['Default'])) {
            $requestedFields = [
                'Default' => $requestedFields['Default'],
            ];
        } else {
            //No Default group means, no fields for comments is available in the field list
            $commentsField = DB::table('approval_form_fields')->where(['field_name' => 'comments', 'model' => null])->first();
            if (isset($commentsField)) {
                $requestedFields[$commentsField->group] = [
                    $commentsField->field_name => [
                        'name' => $commentsField->name,
                        'type' => $commentsField->type,
                        'status' => $commentsField->status,
                    ],
                ];
            }
        }
        $approvalRequestedFields = ApprovalRequestedField::where('approval_id', $id)->first();
        $approval = Approval::find($id);
        foreach ($groupedData as $name => $group) {
            if ($name != 'Approval') {
                $requestedFields[$name] = $group;
            }
        }

        $approval->name = $groupedData['Approval']['name'];
        $approval->description = $groupedData['Approval']['description'];
        $approvalRequestedFields->form_fields = json_encode($requestedFields);
        $approval->save();

        return $approvalRequestedFields->save();
    }
}
