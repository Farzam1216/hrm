<?php


namespace App\Domain\PerformanceReview\Actions;

use Illuminate\Support\Facades\Auth;
use App\Domain\PerformanceReview\Models\PerformanceQuestionnaire;
use App\Domain\PerformanceReview\Actions\StorePerformanceEvaluationViewNotifications;
use App\Domain\PerformanceReview\Actions\StorePerformanceEvaluationDecisionNotifications;

class StoreEvaluationDecision
{
    public function execute($request)
    {
        $questionnaire = PerformanceQuestionnaire::find($request->questionnaire_id);
        $questionnaire->status = $request->decision;
        $questionnaire->comment = $request->comment;
        $questionnaire->decision_authority_id = Auth::id();
        $questionnaire->employee_can_view = $request->employee_can_view;
        $questionnaire->save();

        if ($questionnaire) {
            (new StorePerformanceEvaluationDecisionNotifications())->execute($questionnaire);
        }

        if ($questionnaire->employee_can_view == 1) {
            (new StorePerformanceEvaluationViewNotifications())->execute($questionnaire);
        }

        return $questionnaire;
    }
}
