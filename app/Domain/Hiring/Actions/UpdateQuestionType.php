<?php


namespace App\Domain\Hiring\Actions;

use App\Domain\Hiring\Models\QuestionType;


class UpdateQuestionType
{
    /**
     * @param $id
     * @param $data
     */
    public function execute($request,$id)
    {
        $questionType = QuestionType::find($id);
        $questionType->type = $request->question_type;
        $questionType->save();
    }
}
