<?php


namespace App\Domain\PerformanceReview\Actions;

use App\Domain\PerformanceReview\Models\PerformanceQuestion;
use App\Domain\PerformanceReview\Models\PerformanceForm;

class GetFormWithQuestionsById
{
    public function execute($id)
    {
        $performanceForm = PerformanceForm::where('id', $id)->with('assignedQuestions')->get();
        $questions = PerformanceQuestion::all()->sortBy(function($query){
            return $query->placement;
        });

        return $data = [
            'performanceForm' => $performanceForm,
            'questions' => $questions
        ];
    }
}
