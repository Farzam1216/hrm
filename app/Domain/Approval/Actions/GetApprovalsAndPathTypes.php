<?php


namespace App\Domain\Approval\Actions;

use App\Domain\Approval\Models\Approval;
use App\Domain\Employee\Models\Department;
use App\Domain\Employee\Models\Division;
use App\Domain\Employee\Models\Location;

class GetApprovalsAndPathTypes
{
    /**
     * @return $data
     */
    public function execute()
    {
        $approvals = Approval::with('approvalWorkflow', 'approvalRequestedFields', 'advanceApprovalOptions')->get();
        $departments = Department::all();
        $divison = Division::all();
        $locations = Location::all();
        $data = [
            'approvals' => $approvals,
            'departments' => $departments,
            'divisions' => $divison,
            'locations' => $locations
        ];
        return $data;
    }
}
