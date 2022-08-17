<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Actions\GetAuthorizedUserPermissions;
use App\Domain\Employee\Actions\GetAllEmployeesWithJobDetails;
use App\Domain\Attendance\Actions\GetAllWorkSchedules;

class GetInformationRequiredToCreateAnEmployee
{
    //old Name: CreateEmployee
    public function execute()
    {
        $data['employees'] = (new GetAllEmployees())->execute();
        $data['roles'] = (new GetEmployeeTypeRoles())->execute();
        $data['locations'] = (new GetAllLocations())->execute();
        $data['departments'] = (new GetAllDepartments())->execute();
        $data['designations'] = (new GetAllDesignationsWithEmployee())->execute();
        $data['employmentStatus'] = (new GetAllEmploymentStatus())->execute();
        $data['import'] = (new GetAllEmployeesWithJobDetails())->execute();
        $data['permissions'] = (new GetAuthorizedUserPermissions())->execute($data['import']['employee']);
        $data['workSchedules'] = (new GetAllWorkSchedules())->execute();
        
        return $data;
    }
}
