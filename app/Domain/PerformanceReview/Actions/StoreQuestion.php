<?php


namespace App\Domain\PerformanceReview\Actions;

use App\Domain\PerformanceReview\Models\PerformanceQuestion;
use App\Domain\PerformanceReview\Models\PerformanceQuestionOption;

class StoreQuestion
{
    public function execute($request)
    {
        $question = new PerformanceQuestion();
        $question->question = $request->question;
        $question->field_type = $request->field_type;
        $question->placement = $request->placement;
        $question->save();

        if ($request->field_type == 'multiple choice button') {
            if ($request->options != null) {
                foreach ($request->options as $key => $option) {
                    if ($option != null) {
                        $data = [
                            'option' => $option,
                            'question_id' => $question->id,
                            'created_at' => \Carbon\Carbon::now(),
                            'updated_at' => \Carbon\Carbon::now()
                        ];
                    } else {
                        $question->destroy($question->id);
                        return $data = ['options' => 'null'];
                    }
                    $optionData[$key+1] = $data;
                }
                $options = PerformanceQuestionOption::insert($optionData);
            } else {
                $question->destroy($question->id);
                return $data = ['options' => false];
            }
        }

        return $question;
    }
}
