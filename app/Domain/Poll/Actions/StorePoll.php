<?php

namespace App\Domain\Poll\Actions;

use App\Domain\Poll\Models\Poll;
use Illuminate\Database\Eloquent\Model;
use Auth;


class StorePoll
{
    /**
     * add polls
     * @param $request
     */
    public function execute($data)
    {
        $start_end_date = explode(" to ", $data['start_end_date']);
        $poll = new Poll();
        $poll->employee_id = Auth::id();
        $poll->title = $data['title'];
        $poll->poll_description = !empty($data['description']) ? $data['description'] : '' ;
        $poll->poll_start_date = $start_end_date[0];
        $poll->poll_end_date = $start_end_date[1];

        if ($poll->save()) {
            return true;
        } else {
            return false;
        }
    }
}
