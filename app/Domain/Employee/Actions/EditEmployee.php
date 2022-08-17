<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Approval\Actions\GetRequesterApprovals;
use App\Domain\Approval\Models\ApprovalRequestedDataField;
use App\Domain\Attendance\Actions\GetAllWorkSchedules;

class EditEmployee
{
    public function execute($id, $controller)
    {
        (new ToggleEmployeeBasedMenuItems())->execute($id);
        $data['info'] = (new GetInformationRequiredToEditEmployee())->execute($id);
        $data['workSchedules'] = (new GetAllWorkSchedules())->execute();
        //Authorize current User
        (new AuthorizeUser())->execute('edit', $controller, 'Employee', [$data['info']['employee']]);

        $data['permissions'] = (new GetAuthorizedUserPermissions())->execute([$data['info']['employee']]);
        $data['selectedRole'] = (new GetSelectedEmployeeRole())->execute($data['info']['employee']);
        $data['education'] = (new GetEmployeeEducationInformation())->execute($id);
        $data['visa'] = (new GetEmployeeVisaInformation())->execute($id);
        $data['locations'] = (new GetAllLocations())->execute();
        $data['departments'] = (new GetAllDepartments())->execute();
        $data['designations'] = (new GetAllDesignationsWithEmployee())->execute();
        $data['divisions'] = (new GetAllDivisions())->execute();
        $data['employmentStatus'] = (new GetAllEmploymentStatus())->execute();
        $data['employees'] = (new GetAllEmployees())->execute();
        $data['jobs'] = (new GetEmployeeJobInformation())->execute($id);
        $data['employeeEmploymentStatuses'] = (new GetEmployeeEmploymentStatuses())->execute($id);
        $data['requestApproval'] = (new GetRequesterApprovals())->execute('Standard', $id);
        $data['requestApproval'] = $data['requestApproval']->merge((new GetRequesterApprovals())->execute('Custom', $id));
        //For Testing Purpose Only
        $data['requests'] = ApprovalRequestedDataField::all()->pluck('id');

        return $data;
    }
}
