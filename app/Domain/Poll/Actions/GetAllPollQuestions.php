<?php

namespace App\Domain\Poll\Actions;

use App\Domain\Poll\Models\PollQuestion;
use App\Domain\Poll\Models\Poll;


class GetAllPollQuestions
{
    /**
     * @param $pollId
     * Get Poll All Questions with Poll Id
     */
    public function execute($pollId)
    {
        $poll = Poll::find($pollId);
        $pollQuestions = PollQuestion::where('poll_id', $pollId)->with(['options'])->get();
        $data['pollQuestions'] =  $pollQuestions;
        $data['poll'] =  $poll;
        return $data;
    }
}
