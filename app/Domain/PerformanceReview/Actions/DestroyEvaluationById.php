<?php


namespace App\Domain\PerformanceReview\Actions;

use App\Domain\PerformanceReview\Models\PerformanceQuestionnaire;

class DestroyEvaluationById
{
    public function execute($id)
    {
        $questionnaire = PerformanceQuestionnaire::find($id);

        if($questionnaire)
        {
            $questionnaire->delete();
            
            return true;
        }
        else
        {
            return false;
        }
    }
}
