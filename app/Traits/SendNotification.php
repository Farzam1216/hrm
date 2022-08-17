<?php

namespace App\Traits;

use App\Domain\ACL\Models\Role;
use App\Domain\Approval\Models\ApprovalWorkflow;
use App\Domain\Employee\Models\Employee;
use App\Notifications\DefaultApprovalNotification;

trait SendNotification
{
    /**
     * Create a new notification instance.
     * @param int $requester
     * @param int $approvalWorkflow
     * @param array $requestDetail
     * @return void
     */
    public function sendEmailAndNotification($requester, $approvalWorkflow, $requestDetail): void
    {
        $requester = Employee::where('id', $requester)->first();
        $approvalWorkflow = ApprovalWorkflow::where('id', $approvalWorkflow)->first();
        $this->sendEmailAndNotificationForApprovals($requester, $approvalWorkflow, $requestDetail);
    }
    /**
     * Send email to concerned employees in workflow
     * @param Employee $requester
     * @param ApprovalWorkflow $approvalWorkflow
     * @return void
     */
    private function sendEmailAndNotificationForApprovals($requester, $approvalWorkflow, $requestDetail): void
    {
        if (array_key_exists('FullAdmin', json_decode($approvalWorkflow->approval_hierarchy, true))) {
            $this->sendEmailToFullAdmins($requester, $requestDetail);
        }
        if (array_key_exists('AccountOwner', json_decode($approvalWorkflow->approval_hierarchy, true))) {
            $this->sendEmailToAccountOwner($requester, $requestDetail);
        }
        if (array_key_exists('Manager', json_decode($approvalWorkflow->approval_hierarchy, true))) {
            $this->sendEmailToManager($requester, $requestDetail);
        }
        if (array_key_exists('Manager\'sManager', json_decode($approvalWorkflow->approval_hierarchy, true))) {
            $this->sendEmailToManagerOfManager($requester, $requestDetail);
        }
        if (array_key_exists('AccessLevels', json_decode($approvalWorkflow->approval_hierarchy, true))) {
            $this->sendEmailToAccessLevels(json_decode($approvalWorkflow->approval_hierarchy, true), $requester, $requestDetail);
        }
        if (array_key_exists('SpecificPerson', json_decode($approvalWorkflow->approval_hierarchy, true))) {
            $this->sendEmailToSpecificPerson(json_decode($approvalWorkflow->approval_hierarchy, true), $requester, $requestDetail);
        }
    }
    /**
     * Send email to FullAdmins in workflow
     * @param Employee $requester
     * @return void
     */
    private function sendEmailToFullAdmins($requester, $requestDetail): void
    {
        $admins = Employee::role('admin')->get();
        $this->sendEmail($admins->toArray(), $requester, $requestDetail);
    }
    /**
     * Send email to FullAdmins in workflow
     * @param Employee $requester
     * @return void
     */
    private function sendEmailToAccountOwner($requester, $requestDetail): void
    {
        $accountOwner = Employee::where('id', 1)->first();
        $this->sendEmail(collect()->push($accountOwner)->toArray(), $requester, $requestDetail);
    }

    /**
     * Send email to all employees in specific AccessLevels in workflow
     * @param array $accessLevels
     * @param Employee $requester
     * @return void
     */
    private function sendEmailToAccessLevels($accessLevels, $requester, $requestDetail): void
    {
        $accessLevels = $accessLevels['AccessLevels'];
        foreach ($accessLevels as $accessLevel) {
            $role = Role::findById($accessLevel);
            $users = Employee::role($role->name)->get();
            $this->sendEmail($users->toArray(), $requester, $requestDetail);
        }
    }
    /**
     * Send email to Manager in workflow
     * @param Employee $requester
     * @return void
     */
    private function sendEmailToManager($requester, $requestDetail): void
    {
        $employeeManager = $requester->getEmployeeManager();
        $this->sendEmail(collect()->push($employeeManager)->toArray(), $requester, $requestDetail);
    }
    /**
     * Send email to Manager's Manger in workflow
     * @param Employee $requester
     * @return void
     */
    private function sendEmailToManagerOfManager($requester, $requestDetail): void
    {
        $employeeManagerOfManager = $requester->getEmployeeManagerOfManager();
        $this->sendEmail(collect()->push($employeeManagerOfManager)->toArray(), $requester, $requestDetail);
    }
    /**
     * Send email to specific person in workflow
     * @param array $accessLevels
     * @param Employee $requester
     * @return void
     */
    private function sendEmailToSpecificPerson($specificPerson, $requester, $requestDetail): void
    {
        $specificPerson = Employee::find($specificPerson['SpecificPerson']);
        $this->sendEmail(collect()->push($specificPerson)->toArray(), $requester, $requestDetail);
    }
    /**
     * Send emails and notification to concerned employees in approval
     * @param array $employees
     * @param Employee $requester
     * @return mixed
     */
    public function sendEmail(array $employees, $requester, $requestDetail)
    {
        foreach ($employees as $employee) {
            $user = (new Employee)->forceFill([
                'id' => $employee['id'],
                'name' => $employee['firstname'],
                'email' => $employee['official_email'],
            ]);
            $user->notify(new DefaultApprovalNotification($requester, $requestDetail));
        }
    }
}
