<?php


namespace App\Domain\PerformanceReview\Actions;

use App\Domain\PerformanceReview\Models\PerformanceForm;
use App\Domain\PerformanceReview\Models\PerformanceFormQuestion;

class UpdateFormById
{
    public function execute($request, $id)
    {
        $performanceForm = PerformanceForm::where('id', $id)->with('assignedQuestions')->get();
        foreach ($performanceForm as $form) {
            $form->name = $request->name;
            $form->save();

            if (isset($request->questions)) {
                foreach ($form['assignedQuestions'] as $assignedQuestion) {
                    if(!in_array($assignedQuestion->question_id, $request->questions)) {
                        $assignedQuestion->destroy($assignedQuestion->id);
                    }
                }
                foreach ($request->questions as $question_id) {
                    $check = false;

                    foreach ($form['assignedQuestions'] as $assignedQuestion) {
                        if($assignedQuestion->question_id == $question_id) {
                            $check = true;
                        }
                    }

                    if ($check == false) {
                        $formQuestion = new PerformanceFormQuestion();
                        $formQuestion->form_id = $form->id;
                        $formQuestion->question_id = $question_id;
                        $formQuestion->save();
                    }
                }
            } else {
                foreach ($form['assignedQuestions'] as $assignedQuestion) {
                    $assignedQuestion->destroy($assignedQuestion->id);
                }
            }
        }

        return $performanceForm;
    }
}
