<?php

namespace App\Domain\PayRoll\Actions;

use App\Domain\PayRoll\Models\PayScheduleAssign;

class StorePayScheduleAssignment
{
    public function execute($request)
    {
        $assignPaySchedules = PayScheduleAssign::where('pay_schedule_id', $request->pay_schedule_id)->get();
        $assignedCount = count($assignPaySchedules);

        if (isset($request->employeesData)) {
            foreach ($assignPaySchedules as $assignPaySchedule) {
                $checkForm = false;
                foreach ($request->employeesData as $employeeData) {
                    foreach ($employeeData as $employee_id) {
                        if ($assignPaySchedule->employee_id == $employee_id) {
                            $checkForm = true;
                        }
                    }
                }

                if ($checkForm == false) {
                    $assignPaySchedule->destroy($assignPaySchedule->id);
                }
            }

            $assignCount = count($request->employeesData);
            foreach ($request->employeesData as $employeeData) {
                foreach ($employeeData as $employee_id) {
                    $assignmentCheck = PayScheduleAssign::where('employee_id', $employee_id)->first();

                    if (!$assignmentCheck) {
                        $assignmentCheck = new PayScheduleAssign();
                        $assignmentCheck->pay_schedule_id = $request->pay_schedule_id;
                        $assignmentCheck->employee_id = $employee_id;
                        $assignmentCheck->save();
                    } else {
                        $assignmentCheck->pay_schedule_id = $request->pay_schedule_id;
                        $assignmentCheck->employee_id = $employee_id;
                        $assignmentCheck->save();
                    }
                }
            }
        }
        if (!isset($request->employeesData)) {
            foreach ($assignPaySchedules as $assignPaySchedule) {
                $assignPaySchedule->destroy($assignPaySchedule->id);
            }
            $assignCount = 0;
        }

        if ($assignCount == 0 || $assignCount < $assignedCount) {
            return $data = [
                'employee' => true,
                'check' => 'updated',
            ];
        } else {
            return $data = [
                'employee' => true,
                'check' => 'assigned'
            ];
        }
    }
}
