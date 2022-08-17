<?php


namespace App\Domain\Hiring\Actions;

use App\Domain\Hiring\Models\Question;

class GetQuestion
{

    /**
     * @return mixed
     */
    public function execute()
    {
        $questions = Question::where('type_id', 1)->get();
        return $questions;
    }
}
