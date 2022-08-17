<?php


namespace App\Domain\PerformanceReview\Actions;

use App\Domain\Employee\Models\Employee;
use App\Domain\Employee\Actions\GetAuthorizedUserPermissions;
use App\Domain\Employee\Actions\GetAllEmployeesWithJobDetails;
use App\Domain\PerformanceReview\Models\PerformanceQuestionnaire;
use App\Domain\PerformanceReview\Models\PerformanceQuestionnaireAssign;

class GetEmployeesWithAssignedEvaluations
{
    public function execute()
    {
        $employees = Employee::with(['performance_assigned'])->get();

        return $data = [
            'employees' => $employees
        ];
    }
}
