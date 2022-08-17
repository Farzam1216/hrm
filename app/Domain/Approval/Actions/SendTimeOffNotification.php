<?php


namespace App\Domain\Approval\Actions;

use Carbon\Carbon;
use App\Traits\SendNotification;
use Illuminate\Support\Facades\Auth;
use App\Domain\Employee\Models\Employee;
use App\Jobs\RequestTimeOffJob;

class SendTimeOffNotification
{
    use SendNotification;

    /**
     * @param $requestTimeoff
     * @param $data
     * @param $requestTimeoffHoursSum
     * @param $timeOffType
     */
    public function execute($requestTimeoff, $data, $requestTimeoffHoursSum, $timeOffType, $employee_id)
    {
        $employee = Employee::where('id', $employee_id)->with('employeeManager')->first();
        $message =  $employee->getFullNameAttribute() . ' is requesting ' . $requestTimeoffHoursSum . ' hours of ' . $timeOffType->time_off_type_name . ' from ' . $requestTimeoff->to . ' to ' . $requestTimeoff->from;
        $adminUser = Employee::role('admin')->get();
        $employees = Employee::all();
        $allowedEmployees = [];
        $index = 0;

        foreach ($employees as $emp) {
            if (!$emp->isAdmin()) {
                $empPermissions = $emp->getPermissionsViaRoles()->pluck('name');
                $permissions = [];

                foreach ($empPermissions as $key => $empPermission) {
                    $permissions[$key] = $empPermission;
                }
                if (in_array('manage employees PTO', $permissions) == true) {
                    $allowedEmployees[$index++] = $emp;
                }
            }
        }

        $users = $adminUser->merge($allowedEmployees);

        if ($employee->manager_id) {
            $directManagerUser = Employee::where('id', $employee->manager_id)->get();
            $users =  $users->merge($directManagerUser);

            if (isset($employee->employeeManager->manager_id)) {
                $indirectManagerUser = Employee::where('id', $employee->employeeManager->manager_id)->get();
                $users =  $users->merge($indirectManagerUser);
            }
        }

        $title = "New Time Off Request";
        $employeeData['title'] = $title;
        $employeeData['employeeInfo'] = $employee;
        $employeeData['description'] = $message;
        $employeeData['url'] = "/employee/" . $employee->id . "/timeoff";
        
        RequestTimeOffJob::dispatch($employeeData, $users)->delay(Carbon::now()->addMinutes(1));
    }
}
