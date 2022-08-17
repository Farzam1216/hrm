<?php


namespace App\Domain\Hiring\Actions;

use App\Domain\Hiring\Models\Question;
use App\Domain\Hiring\Models\JobQuestion;



class DestroyQuestion
{
    /**
     * @param $id
     * Add Candidate Status in CandidateStatus
     */
    public function execute($id)
    {
        $jobQuestion=JobQuestion::where('que_id', $id)->first();
        if ($jobQuestion) {
            $jobQuestion->delete();
        }
        $question = Question::find($id);
        $question->delete();
    }
}
