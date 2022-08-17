<?php


namespace App\Domain\Hiring\Actions;

use App\Domain\Hiring\Models\Question;

class GetQuestionByID
{

    /**
     * @return mixed
     */
    public function execute($id)
    {
        return Question::where('id', $id)->first();
    }
}
