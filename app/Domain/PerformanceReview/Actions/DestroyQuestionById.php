<?php


namespace App\Domain\PerformanceReview\Actions;

use App\Domain\PerformanceReview\Models\PerformanceQuestion;

class DestroyQuestionById
{
    public function execute($id)
    {
        $questionWithOptions = PerformanceQuestion::where('id', $id)->with('options')->get();
        foreach ($questionWithOptions as $question) {
            if ($question->field_type == "multiple choice button") {
                foreach ($question['options'] as $option) {
                    $option->delete();
                }
            }
            if ($question) {
                $question->delete();
                
                return true;
            } else {
                return false;
            }
        }
    }
}
