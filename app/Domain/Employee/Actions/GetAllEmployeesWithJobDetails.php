<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\Employee;
use App\Domain\Employee\Models\EmploymentStatus;
use Illuminate\Support\Facades\Auth;

class GetAllEmployeesWithJobDetails
{
    //old name ShowEmployeeInformation
    /**
     * @param string $id
     *
     * @return array
     */
    public function execute($id = '')
    {
        $data = [];
        if ('all' == $id) {
            $data['employee'] = Employee::with('Location', 'department', 'employmentStatus', 'employeeManager')->get();
        } elseif ($id == '') {
            $data['employee'] = Employee::orderBy('status', 'desc')->with('Location', 'department', 'employmentStatus', 'employeeManager')->where('id', '<>', 1)->get();
        } else {
            $data['employee'] = Employee::with('Location', 'department', 'employmentStatus', 'employeeManager')
                ->where('employment_status_id', $id)
                ->get();
        }
        $data['active_employees'] = Employee::where('status', '1')->with('employeeManager')->count();
        $data['filter'] = EmploymentStatus::all();

        return $data;
    }
}
