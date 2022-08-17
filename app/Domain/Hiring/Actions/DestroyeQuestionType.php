<?php


namespace App\Domain\Hiring\Actions;

use App\Domain\Hiring\Models\QuestionType;


class DestroyeQuestionType
{

    public function execute($id)
    {
        $questionType = QuestionType::find($id);
        $questionType->delete();
    }
}
