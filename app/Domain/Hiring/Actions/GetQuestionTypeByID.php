<?php


namespace App\Domain\Hiring\Actions;

use App\Domain\Hiring\Models\QuestionType;

class GetQuestionTypeByID
{

    /**
     * @return mixed
     */
    public function execute($id)
    {
        return QuestionType::where('id', $id)->first();
    }
}
