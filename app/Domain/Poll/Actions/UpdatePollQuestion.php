<?php

namespace App\Domain\Poll\Actions;

use App\Domain\Poll\Models\PollQuestion;
use App\Domain\Poll\Models\PollQuestionOption;
use App\Domain\Poll\Actions\StorePollQuestionOptions;
use Illuminate\Database\Eloquent\Model;

class UpdatePollQuestion
{
    /**
     * Update Question and options
     * @param $request
     */
    public function execute($data, $pollId, $questionId)
    {
        $pollQuestion = PollQuestion::find($questionId);
        $pollQuestion->title = $data['title'];

        $pollQuestionOption = PollQuestionOption::where('question_id', $questionId);
        $pollQuestionOption->delete();
        $options = (new StorePollQuestionOptions())->execute($data, $pollId, $questionId);

        if ($pollQuestion->save() && $options) {
            return true;
        } else {
            return false;
        }
    }
}
