<?php

namespace App\Domain\Poll\Actions;

use App\Domain\Poll\Models\PollQuestion;
use Illuminate\Database\Eloquent\Model;

class DeletePollQuestion
{
    /**
     * Delete polls
     */
    public function execute($id)
    {
        $pollQuestion = PollQuestion::find($id);
        if ($pollQuestion->delete()) {
            return true;
        } else {
            return false;
        }
    }
}
