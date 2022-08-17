<?php


namespace App\Domain\Approval\Actions;

use App\Domain\Approval\Models\ApprovalType;
use App\Domain\Employee\Models\Employee;
use Illuminate\Support\Facades\Auth;

class GetRequesterApprovals
{
    /**
     * Get list of approvals for current user.
     *
     * @param mixed $type
     * @param mixed $id
     *
     * @return $requestChangeApprovals;
     */
    public function execute($type, $id)
    {
        $approvalType = ApprovalType::where('name', $type)->first();
        $approvals = $approvalType->approvals()->where('status', 1)->get(); //Only get active/enabled approvals
        $approvalRequesters = collect();
        $currentUser = Auth::user();
        $requestChangeApprovals = collect();
        //Get the Requester (Who can make the request) from Approval Collection Object
        foreach ($approvals as $approval) {
            $approvalRequesters = $approval->approvalRequesters()->get();
            $approvalFields = $approval->approvalRequestedFields()->first();
            foreach ($approvalRequesters as $approvalRequester) {
                $requesterRoles = json_decode($approvalRequester->approval_requester_data);
                $formFields = json_decode($approvalFields->form_fields, true);
                foreach ($requesterRoles as $requester => $value) {
                    $employee = Employee::find($id);
                    if ($requester == 'FullAdmin') {
                        if ($currentUser->isAdmin()) {
                            $requestChangeApprovals = $requestChangeApprovals->prepend($approval);
                            //$requestChangeApprovals[$approval->name][] = $approval;
                        }
                    } elseif ($requester == 'AccountOwner') {
                        if ($currentUser->isAdmin() && $currentUser->id == 1) {
                            $requestChangeApprovals = $requestChangeApprovals->prepend($approval);
                        }
                    } elseif ($requester == 'Manager') {
                        $directEmployees = $currentUser->directEmployees();
                        if ($directEmployees->contains('id', $id)) {
                            $requestChangeApprovals = $requestChangeApprovals->prepend($approval);
                        }
                    } elseif ($requester == "Manager'sManager") {
                        $indirectEmployees = $currentUser->indirectEmployees();
                        if ($indirectEmployees->contains('id', $id)) {
                            $requestChangeApprovals = $requestChangeApprovals->prepend($approval);
                        }
                    } else {
                        if ($currentUser->roles()->where(['name' => $requester, 'id' => $value])->first()) {
                            $requestChangeApprovals = $requestChangeApprovals->prepend($approval);
                        }
                    }
                }
            }
        }

        return $requestChangeApprovals;
    }
}
