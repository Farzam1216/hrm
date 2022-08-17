<?php


namespace App\Domain\Hiring\Actions;

use App\Domain\Hiring\Models\Question;


class UpdateQuestion
{
    /**
     * @param $id
     * Add Candidate Status in CandidateStatus
     */
    public function execute($request,$id)
    {
        $question = Question::find($id);
        $question->question = $request->question;
        $question->fieldType = $request->field;
        $question->save();

    }
}
