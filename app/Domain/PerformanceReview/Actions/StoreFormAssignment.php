<?php

namespace App\Domain\PerformanceReview\Actions;

use App\Domain\PerformanceReview\Models\PerformanceFormAssign;

class StoreFormAssignment
{
    public function execute($request)
    {
        $assignForms = PerformanceFormAssign::where('form_id', $request->form_id)->get();
        $assignedCount = count($assignForms);

        if (isset($request->employeesData)) {
            foreach ($assignForms as $assignForm) {
                $checkForm = false;
                foreach ($request->employeesData as $employeeData) {
                    foreach ($employeeData as $employee_id) {
                        if ($assignForm->employee_id == $employee_id) {
                            $checkForm = true;
                        }
                    }
                }

                if ($checkForm == false) {
                    $assignForm->destroy($assignForm->id);
                }
            }

            $assignCount = count($request->employeesData);
            foreach ($request->employeesData as $employeeData) {
                foreach ($employeeData as $employee_id) {
                    $assignmentCheck = PerformanceFormAssign::where('employee_id', $employee_id)->first();

                    if (!$assignmentCheck) {
                        $assignmentCheck = new PerformanceFormAssign();
                        $assignmentCheck->form_id = $request->form_id;
                        $assignmentCheck->employee_id = $employee_id;
                        $assignmentCheck->save();
                    } else {
                        $assignmentCheck->form_id = $request->form_id;
                        $assignmentCheck->employee_id = $employee_id;
                        $assignmentCheck->save();
                    }
                }
            }
        }
        if (!isset($request->employeesData)) {
            foreach ($assignForms as $assignForm) {
                $assignForm->destroy($assignForm->id);
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
