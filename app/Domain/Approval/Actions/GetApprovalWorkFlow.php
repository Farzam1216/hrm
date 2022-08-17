<?php


namespace App\Domain\Approval\Actions;

use App\Domain\Approval\Models\AdvanceApprovalOption;
use App\Domain\Approval\Models\ApprovalWorkflow;
use App\Domain\Employee\Models\Employee;

class GetApprovalWorkFlow
{
    /**
     * @param $id
     * @return mixed
     */
    public function execute($id)
    {
        $employee = Employee::find($id);
        $approvalWorkflow = ApprovalWorkflow::where('approval_id', 1)->first();
        $advanceApprovalOptions = AdvanceApprovalOption::where('approval_id', 1)->orderBy('created_at', 'desc')->get();
        if (count($advanceApprovalOptions) == 0) {
            return $approvalWorkflow;
        } else {
            foreach ($advanceApprovalOptions as $advanceApprovalOption) {
                if ($advanceApprovalOption->approval_path != null) {
                    $advanceApprovalPath = json_decode($advanceApprovalOption->approval_path);
                    //check id employee who is request for updating is part of that approval path
                    if ($advanceApprovalOption->advance_approval_type == "Department") {
                        if (in_array($employee->department->department_name, $advanceApprovalPath)) {
                            $advanceApprovalWorkflow = ApprovalWorkflow::where('approval_id', 1)->where(
                                'level_number',
                                1
                            )->where(
                                'advance_approval_option_id',
                                $advanceApprovalOption->id
                            )->first();
                            return $advanceApprovalWorkflow;
                        }
                    } else {
                        if ($advanceApprovalOption->advance_approval_type == 'Location') {
                            if (in_array($employee->Location->name, $advanceApprovalPath)) {
                                $advanceApprovalWorkflow = ApprovalWorkflow::where('approval_id', 1)->where(
                                    'level_number',
                                    1
                                )->where(
                                    'advance_approval_option_id',
                                    $advanceApprovalOption->id
                                )->first();
                                return $advanceApprovalWorkflow;
                            }
                        }
                    }
                } else {
                    $advanceApprovalWorkflow = ApprovalWorkflow::where('approval_id', 1)->where(
                        'level_number',
                        1
                    )->whereNull(
                        'advance_approval_option_id'
                    )->first();
                    return $advanceApprovalWorkflow;
                }
            }
        }
    }
}
