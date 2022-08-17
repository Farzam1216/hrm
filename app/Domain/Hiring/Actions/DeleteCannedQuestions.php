<?php


namespace App\Domain\Hiring\Actions;

use App\Domain\Hiring\Models\JobQuestion;

class DeleteCannedQuestions
{
    /**
     * @param $id
     * @param $data
     */
    public function execute($id, $data)
    {
        $cannedQuestions = JobQuestion::where('job_id', $id)->get();
        if ($cannedQuestions) {
            foreach ($cannedQuestions as $ques) {
                foreach ($data as $d) {
                    if ($d['id'] == $ques->que_id && !isset($d['question'])) {
                        $ques->delete();
                    }
                }
            }
        }
    }
}
