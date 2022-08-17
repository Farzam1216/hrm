<?php


namespace App\Domain\Hiring\Actions;

use App\Domain\Hiring\Models\QuestionType;


class StoreQuestionType
{
    /**
     * Store question type
     */
    public function execute($request)
    {
        $question_type_exist = QuestionType::where('type', $request->question_type)->first();
        if ($question_type_exist == null) {
            $question_type_exist = QuestionType::create([
                'type' => $request->question_type,
            ]);
            return true;
        }
        return false;

    }
}
