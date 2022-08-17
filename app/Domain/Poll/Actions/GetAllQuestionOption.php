<?php

namespace App\Domain\Poll\Actions;

use App\Domain\Poll\Models\PollQuestion;


class GetAllQuestionOption
{
    /**
     * @param $id
     * Get Question and its assosicated options with questionId
     */
    public function execute($questionId)
    {

        $questionOptions= PollQuestion::where('id',$questionId)->with(['options'])->get();
        return $questionOptions;
        
    }
}
