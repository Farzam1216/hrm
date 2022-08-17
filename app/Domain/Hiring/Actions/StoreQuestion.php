<?php


namespace App\Domain\Hiring\Actions;

use App\Domain\Hiring\Models\Question;


class StoreQuestion
{
    /**
     * @param $id
     * Add Candidate Status in CandidateStatus
     */
    public function execute($request)
    {
        $question_exist = Question::where('question', $request->question)->get();
       
        if ($question_exist->count() == 0) {
            $question = Question::create(
                [
                    'question' => $request->question,
                    'fieldType' => $request->field,
                    'type_id' => '1'
    
                ]
            );
            return true;
        }
        return false;
    }
}
