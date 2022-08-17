<?php

namespace App\Domain\Poll\Actions;

use App\Domain\Poll\Models\PollQuestionOption;
use Illuminate\Database\Eloquent\Model;
use Auth;


class StorePollQuestionOptions
{
    /**
     * Store Options of Questions
     * @param $request
     */
    public function execute($data, $pollId, $questionId)
    {
        
        foreach ($data['question_option'] as $option) {
            $pollQuestionOption = new PollQuestionOption();
            $pollQuestionOption->option_name = $option;
            $pollQuestionOption->poll_id = $pollId;
            $pollQuestionOption->question_id = $questionId;
            $pollQuestionOption->save();
        }
        if ($pollQuestionOption) {
            return true;
        } else {
            return false;
        }
    }
}
