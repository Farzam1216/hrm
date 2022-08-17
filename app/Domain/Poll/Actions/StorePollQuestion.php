<?php

namespace App\Domain\Poll\Actions;

use App\Domain\Poll\Models\PollQuestion;
use Illuminate\Database\Eloquent\Model;
use Auth;


class StorePollQuestion
{
    /**
     * Store Poll Questions
     * @param $request
     */
    public function execute($data, $pollId)
    {

        $pollQuestion = new PollQuestion();
        $pollQuestion->employee_id = Auth::id();
        $pollQuestion->title = $data['title'];
        $pollQuestion->poll_id = $pollId;
        $pollQuestion->question_type = '';
        if ($pollQuestion->save()) {
            return $pollQuestion->id;
        } else {
            return false;
        }
    }
}
