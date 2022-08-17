<?php


namespace App\Domain\PerformanceReview\Actions;

use Illuminate\Support\Facades\Auth;
use App\Domain\PerformanceReview\Models\PerformanceQuestion;
use App\Domain\PerformanceReview\Models\PerformanceQuestionnaire;
use App\Domain\PerformanceReview\Models\PerformanceQuestionnaireAnswer;
use App\Domain\PerformanceReview\Actions\StorePerformanceEvaluationSubmitNotifications;

class StoreEvaluation
{
    public function execute($request)
    {
        $questionnaire = new PerformanceQuestionnaire();
        $questionnaire->employee_id = $request->employee_id;
        $questionnaire->submitter_id = Auth::id();
        $questionnaire->save();

        foreach ($request->answer as $key => $answer) {
            $question = PerformanceQuestion::find($key);
            $answer = new PerformanceQuestionnaireAnswer();

            if ($question->field_type == 'multiple choice button') {
                $answer->questionnaire_id = $questionnaire->id;
                $answer->question_id = $question->id;
                $answer->option_id = $request->answer[$question->id];
            } else {
                $answer->questionnaire_id = $questionnaire->id;
                $answer->question_id = $question->id;
                $answer->answer = $request->answer[$question->id];
            }
            $answer->save();
        }

        if ($questionnaire) {
            (new StorePerformanceEvaluationSubmitNotifications())->execute($questionnaire->employee_id, $questionnaire->submitter_id);
        }

        return $questionnaire;
    }
}
