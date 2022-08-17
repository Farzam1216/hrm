<?php


namespace App\Domain\PerformanceReview\Actions;

use App\Domain\Employee\Models\Employee;
use App\Domain\PerformanceReview\Models\PerformanceQuestionnaireAssign;
use App\Domain\PerformanceReview\Actions\StorePerformanceEvaluationAssignmentNotifications;
use App\Domain\PerformanceReview\Actions\StorePerformanceEvaluationUnassignmentNotifications;

class StoreEvaluationAssignment
{
    public function execute($request)
    {
        $assignQuestionnaires = PerformanceQuestionnaireAssign::all();
        $assignedCount = count($assignQuestionnaires);

        if (isset($request->employeesData)) {
            foreach ($assignQuestionnaires as $assignQuestionnaire) {
                $checkQuestionnaire = false;
                foreach ($request->employeesData as $employeeData) {
                    foreach ($employeeData as $employee_id) {
                        if ($assignQuestionnaire->employee_id == $employee_id) {
                            $checkQuestionnaire = true;
                        }
                    }
                }

                if ($checkQuestionnaire == false) {
                    (new StorePerformanceEvaluationUnassignmentNotifications())->execute($assignQuestionnaire->employee_id, $assignQuestionnaire->manager_id);
                    $assignQuestionnaire->destroy($assignQuestionnaire->id);
                }
            }

            $assignCount = count($request->employeesData);
            foreach ($request->employeesData as $employeeData) {
                foreach ($employeeData as $employee_id) {
                    $assignEmployee = Employee::find($employee_id);

                    if($assignEmployee->manager_id == null) {
                        return $data = [
                            'employee' => false,
                            'employee_name' => $assignEmployee->firstname . ' ' . $assignEmployee->lastname
                        ];
                    }

                    $assignmentCheck = PerformanceQuestionnaireAssign::where('employee_id', $employee_id)->first();

                    if (!$assignmentCheck) {
                        (new StorePerformanceEvaluationAssignmentNotifications())->execute($assignEmployee->id, $assignEmployee->manager_id);

                        $assignQuestionnaire = new PerformanceQuestionnaireAssign();
                        $assignQuestionnaire->employee_id = $assignEmployee->id;
                        $assignQuestionnaire->manager_id = $assignEmployee->manager_id;
                        $assignQuestionnaire->save();
                    }
                }
            }
        }
        if (!isset($request->employeesData)) {
            foreach ($assignQuestionnaires as $assignQuestionnaire) {
                (new StorePerformanceEvaluationUnassignmentNotifications())->execute($assignQuestionnaire->employee_id, $assignQuestionnaire->manager_id);
                $assignQuestionnaire->destroy($assignQuestionnaire->id);
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
