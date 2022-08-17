<?php


namespace App\Domain\Approval\Actions;

use App\Domain\Approval\Models\AdvanceApprovalOption;
use App\Domain\Approval\Models\AdvanceApprovalWorkflow;
use App\Domain\Approval\Models\ApprovalRequester;
use App\Domain\Approval\Models\ApprovalWorkflow;

class StoreAdvanceApprovalHierarchy
{
    /**
     * @param $hierarchyData, $levelNumber, $data
     * @return
     */

    public function execute($hierarchyData, $levelNumber, $data, &$flag)
    {
        $approvalID = $data['approvalId'];

        if (isset($data['advanceApprovalOptionId'])) {
            //update existing workflow
            AdvanceApprovalOption::where(
                'id',
                $data['advanceApprovalOptionId']
            )->update(['approval_path' => json_encode($data['approvalPath'])]);
            $workflow = ApprovalWorkflow::updateOrCreate(
                [
                    'level_number' => $levelNumber,
                    'approval_id' => $approvalID,
                    'advance_approval_option_id' => $data['advanceApprovalOptionId']
                ],
                ['approval_hierarchy' => json_encode($hierarchyData)]
            );
            AdvanceApprovalWorkflow::updateOrCreate(
                [
                    'approval_workflow_id' => $workflow->id,
                    'advance_approval_option_id' => $data['advanceApprovalOptionId']
                ],
                [
                    'approval_workflow_id' => $workflow->id,
                    'advance_approval_option_id' => $data['advanceApprovalOptionId']
                ]
            );
            if ($approvalID > 2) {
                ApprovalRequester::updateOrCreate(
                    ['approval_id' => $approvalID, 'advance_approval_option_id' => $data['advanceApprovalOptionId']],
                    ['approval_requester_data' => json_encode($data['makeTypeofRequest'])]
                );
            }
        } else {
            //store advance workflow
            if ($flag) {
                $advanceApprovalOption = AdvanceApprovalOption::create(
                    [
                        'approval_id' => $approvalID,
                        'advance_approval_type' => $data['advance_approval_type'],
                        'approval_path' => json_encode($data['approvalPath'])
                    ]
                );
                session(['advanceApprovalOptionID'=> $advanceApprovalOption->id]);
                $flag = false;
            }
            $advanceApprovalOptionId = session('advanceApprovalOptionID');
            $workflow = ApprovalWorkflow::updateOrCreate(
                [
                    'level_number' => $levelNumber,
                    'approval_id' => $approvalID,
                    'advance_approval_option_id' => $advanceApprovalOptionId
                ],
                ['approval_hierarchy' => json_encode($hierarchyData)]
            );
            AdvanceApprovalWorkflow::updateOrCreate(
                ['approval_workflow_id' => $workflow->id, 'advance_approval_option_id' => $advanceApprovalOptionId],
                ['approval_workflow_id' => $workflow->id, 'advance_approval_option_id' => $advanceApprovalOptionId]
            );
            if ($approvalID > 2) {
                ApprovalRequester::updateOrCreate(
                    ['approval_id' => $approvalID, 'advance_approval_option_id' => $advanceApprovalOptionId],
                    ['approval_requester_data' => json_encode($data['makeTypeofRequest'])]
                );
            }
        }
    }
}
