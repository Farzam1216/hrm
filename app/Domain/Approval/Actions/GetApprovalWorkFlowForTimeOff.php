<?php


namespace App\Domain\Approval\Actions;

use App\Domain\Approval\Models\AdvanceApprovalOption;
use App\Domain\Approval\Models\ApprovalWorkflow;
use App\Domain\Employee\Models\Employee;

class GetApprovalWorkFlowForTimeOff
{
    /**
     * @param $id
     * @return mixed
     */
    public function execute($id)
    {
        $employee = Employee::find($id);
        $advanceApprovalOptions = AdvanceApprovalOption::where('approval_id', 2)->orderBy('created_at', 'desc')->get();
        foreach ($advanceApprovalOptions as $advanceApprovalOption) {
            if ($advanceApprovalOption->approval_path != null) {
                $advanceApprovalPath = json_decode($advanceApprovalOption->approval_path);
                //check id employee who is request for updating is part of that approval path
                if ($advanceApprovalOption->advance_approval_type == "Department") {
                    if (isset($employee->department->department_name) && in_array($employee->department->department_name, $advanceApprovalPath)) {
                        $advanceApprovalWorkflow = ApprovalWorkflow::where('approval_id', 2)
                            ->where('level_number', 1)->where('advance_approval_option_id', $advanceApprovalOption->id)->first();
                        return $advanceApprovalWorkflow;
                    }
                } elseif ($advanceApprovalOption->advance_approval_type == 'Location') {
                    if (isset($employee->Location->name) && in_array($employee->Location->name, $advanceApprovalPath)) {
                        $advanceApprovalWorkflow = ApprovalWorkflow::where('approval_id', 2)
                            ->where('level_number', 1)->where('advance_approval_option_id', $advanceApprovalOption->id)->first();
                        return $advanceApprovalWorkflow;
                    }
                }
            } else {
                $advanceApprovalWorkflow = ApprovalWorkflow::where('approval_id', 2)
                    ->where('level_number', 1)->whereNull('advance_approval_option_id')->first();
                return $advanceApprovalWorkflow;
            }
        }
    }
}
