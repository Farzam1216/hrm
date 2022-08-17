<?php

namespace App\Domain\Poll\Actions;

use App\Domain\Poll\Models\PollAnswer;
use Illuminate\Database\Eloquent\Model;
use Auth;


class StorePollAnswers
{
    /**
     * add answers
     * @param $request
     */
    public function execute($data, $pollId)
    {

        foreach ($data['answer'] as $a) {
            $pollAnswer = new PollAnswer();
            $questionAnswerIds = explode("-", $a);
            $pollAnswer->question_id = $questionAnswerIds[0];
            $pollAnswer->option_id = $questionAnswerIds[1];
            $pollAnswer->employee_id = Auth::id();
            $pollAnswer->poll_id = $pollId;
            $pollAnswer->save();
        }
        if ($pollAnswer) {
            return true;
        } else {
            return false;
        }
    }
}
