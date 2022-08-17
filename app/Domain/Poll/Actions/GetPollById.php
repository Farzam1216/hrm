<?php

namespace App\Domain\Poll\Actions;

use App\Domain\Poll\Models\Poll;


class GetPollById
{
    /**
     * @param $id
     * Get Poll with Id
     */
    public function execute($id)
    {
        $poll = Poll::find($id);
        return $poll;
    }
}
