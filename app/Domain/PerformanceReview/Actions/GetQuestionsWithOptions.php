<?php

namespace App\Domain\PerformanceReview\Actions;

use App\Domain\Employee\Models\Employee;
use App\Domain\PerformanceReview\Models\PerformanceQuestion;

class GetQuestionsWithOptions
{
    public function execute($employee_id)
    {
        $questions = PerformanceQuestion::with('options')->get()->sortBy(function($query){
            return $query->placement;
        });
        $employee = Employee::where('id', $employee_id)->with(['assignedForm', 'assignedForm.assignedQuestions'])->first();

        if (!$employee->assignedForm) {
            return false;
        }

        return $data = [
        	'questions' => $questions,
            'employee' => $employee
        ];
    }
}
