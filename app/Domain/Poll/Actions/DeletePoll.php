<?php

namespace App\Domain\Poll\Actions;

use App\Domain\Poll\Models\Poll;
use Illuminate\Database\Eloquent\Model;

class DeletePoll
{
    /**
     * Delete polls
     */
    public function execute($id)
    {
        $poll = Poll::find($id);
        if ($poll->delete()) {
            return true;
        } else {
            return false;
        }
    }
}
