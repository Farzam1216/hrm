<?php


namespace App\Domain\Holiday\Actions;

use Carbon\Carbon;
use App\Domain\Employee\Models\EmployeeHoliday;

class AssignHolidayToEmployees
{
    /**
     * @return mixed
     */
    public function execute($request, $holiday, $employees)
    {
        $assignHoliday = '';
        if ($request->assign_to == "all employees") {
            foreach ($employees as $employee) {
                if (!$employee->isAdmin()) {
                    $data = [
                        "holiday_id" => $holiday->id,
                        "employee_id" => $employee->id
                    ];
                    $assignHoliday = EmployeeHoliday::UpdateOrCreate($data);
                }
            }
        } else {
            if (isset($request->employees['department'])) {
                foreach ($employees as $employee) {
                    if (!$employee->isAdmin()) {
                        foreach ($request->employees['department'] as $department_id) {
                            if (isset($employee['jobs'][0]) && $employee['jobs'][0]->department_id == $department_id) {
                                $data = [
                                    "holiday_id" => $holiday->id,
                                    "employee_id" => $employee->id
                                ];
                                $assignHoliday = EmployeeHoliday::UpdateOrCreate($data);
                            }
                        }
                    }
                }
            }
            if (isset($request->employees['division'])) {
                foreach ($employees as $employee) {
                    if (!$employee->isAdmin()) {
                        foreach ($request->employees['division'] as $division_id) {
                            if (isset($employee['jobs'][0]) && $employee['jobs'][0]->division_id == $division_id) {
                                $data = [
                                    "holiday_id" => $holiday->id,
                                    "employee_id" => $employee->id
                                ];
                                $assignHoliday = EmployeeHoliday::UpdateOrCreate($data);
                            }
                        }
                    }
                }
            }
            if (isset($request->employees['employment_status'])) {
                foreach ($employees as $employee) {
                    if (!$employee->isAdmin()) {
                        foreach ($request->employees['employment_status'] as $employment_status_id) {
                            if (isset($employee['employment_status'][0]) && $employee['employment_status'][0]->employment_status_id == $employment_status_id) {
                                $data = [
                                    "holiday_id" => $holiday->id,
                                    "employee_id" => $employee->id
                                ];
                                $assignHoliday = EmployeeHoliday::UpdateOrCreate($data);
                            }
                        }
                    }
                }
            }
            if (isset($request->employees['designation'])) {
                foreach ($employees as $employee) {
                    if (!$employee->isAdmin()) {
                        foreach ($request->employees['designation'] as $designation_id) {
                            if (isset($employee['jobs'][0]) && $employee['jobs'][0]->designation_id == $designation_id) {
                                $data = [
                                    "holiday_id" => $holiday->id,
                                    "employee_id" => $employee->id
                                ];
                                $assignHoliday = EmployeeHoliday::UpdateOrCreate($data);
                            }
                        }
                    }
                }
            }
            if (isset($request->employees['location'])) {
                foreach ($employees as $employee) {
                    if (!$employee->isAdmin()) {
                        foreach ($request->employees['location'] as $location_id) {
                            if (isset($employee['jobs'][0]) && $employee['jobs'][0]->location_id == $location_id) {
                                $data = [
                                    "holiday_id" => $holiday->id,
                                    "employee_id" => $employee->id
                                ];
                                $assignHoliday = EmployeeHoliday::UpdateOrCreate($data);
                            }
                        }
                    }
                }
            }
        }

        return $assignHoliday;
    }
}
