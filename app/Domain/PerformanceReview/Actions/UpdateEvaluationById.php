<?php


namespace App\Domain\PerformanceReview\Actions;

use App\Domain\PerformanceReview\Models\PerformanceQuestion;
use App\Domain\PerformanceReview\Models\PerformanceQuestionnaireAnswer;

class UpdateEvaluationById
{
    public function execute($request)
    {
        foreach ($request->question_id as $question_id) {
            $answer = PerformanceQuestionnaireAnswer::where('questionnaire_id', $request->questionnaire_id)->where('question_id', $question_id)->first();

            if(isset($answer->id)){
                if ($answer->option_id) {
                    $answer->option_id = $request->answer[$question_id];
                } else {
                    $answer->answer = $request->answer[$question_id];
                }
            }
            $answer->save();
        }

        return $answer;
    }
}
