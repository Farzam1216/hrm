<?php


namespace App\Domain\Hiring\Actions;

use App\Domain\Hiring\Models\QuestionType;

class GetQuestionType
{
    /**
     * @return QuestionType[]|\Illuminate\Database\Eloquent\Collection
     */
    public function execute()
    {
        $questionTypes = QuestionType::all();
        return $questionTypes;
    }
}
