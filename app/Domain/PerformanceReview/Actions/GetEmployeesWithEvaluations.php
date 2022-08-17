<?php


namespace App\Domain\PerformanceReview\Actions;

use App\Domain\Employee\Models\Employee;
use App\Domain\Employee\Actions\GetAuthorizedUserPermissions;
use App\Domain\Employee\Actions\GetAllEmployeesWithJobDetails;
use App\Domain\PerformanceReview\Models\PerformanceQuestionnaire;
use App\Domain\PerformanceReview\Models\PerformanceQuestionnaireAssign;

class GetEmployeesWithEvaluations
{
    public function execute()
    {
        $employees = Employee::with(['performance_assigned', 'performance_assigned.manager', 'assignedForm', 'assignedForm.form'])->get();
        $questionnaires = PerformanceQuestionnaire::all();
        $assignments = PerformanceQuestionnaireAssign::with('manager')->get();
        $data['info'] = (new GetAllEmployeesWithJobDetails())->execute();
        $data['permissions'] = (new GetAuthorizedUserPermissions())->execute($data['info']['employee']);

        foreach ($employees as $employee) {
            foreach ($questionnaires as $questionnaire) {
                if ($employee->id == $questionnaire->employee_id) {
                    $employee->questionnaire = true;
                }
            }
        }

        return $data = [
            'employees' => $employees,
            'assignments' => $assignments,
            'permissions' => $data['permissions']
        ];
    }
}
