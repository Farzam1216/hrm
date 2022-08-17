<?php


namespace App\Domain\PerformanceReview\Actions;

use App\Domain\Employee\Models\Employee;
use App\Domain\PerformanceReview\Models\PerformanceQuestion;
use App\Domain\Employee\Actions\GetAuthorizedUserPermissions;
use App\Domain\Employee\Actions\GetAllEmployeesWithJobDetails;
use App\Domain\PerformanceReview\Models\PerformanceQuestionnaire;

class GetEvaluationsByEmployeeId
{
    public function execute($employee_id)
    {
        $questionnaires = PerformanceQuestionnaire::where('employee_id', $employee_id)->with(['employees', 'submitters', 'decision_authority'])->get();
        $employee = Employee::find($employee_id);
        $data['info'] = (new GetAllEmployeesWithJobDetails())->execute();
        $data['permissions'] = (new GetAuthorizedUserPermissions())->execute($data['info']['employee']);

        return $data = [
            'employee' => $employee,
            'questionnaires' => $questionnaires,
            'permissions' => $data['permissions']
        ];
    }
}
