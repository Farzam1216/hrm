<?php


namespace App\Domain\PerformanceReview\Actions;

use App\Domain\PerformanceReview\Models\PerformanceQuestion;
use App\Domain\Employee\Actions\GetAuthorizedUserPermissions;
use App\Domain\Employee\Actions\GetAllEmployeesWithJobDetails;
use App\Domain\PerformanceReview\Models\PerformanceQuestionnaire;
use App\Domain\PerformanceReview\Models\PerformanceQuestionHistory;
use App\Domain\PerformanceReview\Models\PerformanceQuestionOptionHistory;

class GetEvaluationWithQuestionsById
{
    public function execute($id)
    {
        $questionnaire = PerformanceQuestionnaire::where('id', $id)->with(['answers', 'submitters', 'decision_authority'])->get();
        $questions = PerformanceQuestion::with(['options'])->get();

        $questionsHistory = PerformanceQuestionHistory::where('created_at', '>', $questionnaire[0]->created_at)->get();
        $optionsHistory = PerformanceQuestionOptionHistory::where('created_at', '>', $questionnaire[0]->created_at)->get();

        $data['info'] = (new GetAllEmployeesWithJobDetails())->execute();
        $data['permissions'] = (new GetAuthorizedUserPermissions())->execute($data['info']['employee']);

        return $data = [
            'questions' => $questions,
            'questionsHistory' => $questionsHistory,
            'optionsHistory' => $optionsHistory,
            'questionnaire' => $questionnaire,
            'permissions' => $data['permissions']
        ];
    }
}
