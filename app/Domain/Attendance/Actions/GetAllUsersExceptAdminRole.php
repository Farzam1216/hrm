<?php


namespace App\Domain\Attendance\Actions;


use App\Domain\Attendance\Models\WorkSchedule;
use App\Domain\Employee\Models\Employee;
use Illuminate\Database\Eloquent\Model;

class GetAllUsersExceptAdminRole
{
    /**
     * add available plans in benefitGroup
     * @param $request
     */
    public function execute()
    {
        $allEmployees = Employee::all();
        $employees = [];
        foreach ($allEmployees as $employee){
            if(!$employee->isAdmin()){
                $employees[] = $employee;
            }
        }

        return $employees;
    }
}
