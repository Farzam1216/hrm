<?php


namespace App\Domain\PerformanceReview\Actions;

use App\Domain\PerformanceReview\Models\PerformanceForm;
use App\Domain\PerformanceReview\Models\PerformanceFormQuestion;

class StoreForm
{
    public function execute($request)
    {
        $form = new PerformanceForm();
        $form->name = $request->name;
        $form->save();

        foreach ($request->questions as $question_id) {
            $formQuestion = new PerformanceFormQuestion();
            $formQuestion->form_id = $form->id;
            $formQuestion->question_id = $question_id;
            $formQuestion->save();
        }

        return $form;
    }
}
