<?php


namespace App\Domain\PerformanceReview\Actions;

use App\Domain\PerformanceReview\Models\PerformanceQuestion;
use App\Domain\PerformanceReview\Models\PerformanceQuestionOption;

class UpdateQuestionById
{
    public function execute($request)
    {
        $question = PerformanceQuestion::where('id', $request->question_id)->with('options')->get();
        foreach ($question as $quest) {
            $quest->question = $request->question;
            $quest->field_type = $request->field_type;
            $quest->placement = $request->placement;

            if ($request->field_type == 'multiple choice button') {
                if ($request->options != null) {                    
                    foreach ($request->options as $key => $option) {
                        if ($option != null) {
                            $data = [
                                'option' => $option,
                                'question_id' => $quest->id,
                                'created_at' => $question[0]->created_at,
                                'updated_at' => \Carbon\Carbon::now()
                            ];
                        } else {
                            return $data = ['options' => 'null'];
                        }
                        $optionData[$key+1] = $data;
                    }
                    if($quest['options'] != '[]'){
                        foreach ($quest['options'] as $option) {
                            $option->destroy($option->id);
                        }
                    }
                    $options = PerformanceQuestionOption::insert($optionData);
                } else {
                    return $data = ['options' => false];
                }
            }
            $quest->save();
        }

        return $quest;
    }
}
