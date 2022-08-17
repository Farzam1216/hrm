<?php

namespace App\Domain\Poll\Actions;

use App\Domain\Poll\Models\PollAnswer;



class GetPollAnswerWithId
{
    /**
     * @param $id
     * @param $data
     */
    public function execute($pollId, $employeeId)
    {
        $matchThese = ['poll_id' => $pollId, 'employee_id' => $employeeId];
        return PollAnswer::where($matchThese)->first();
    }
}
