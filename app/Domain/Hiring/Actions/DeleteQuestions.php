<?php


namespace App\Domain\Hiring\Actions;

use App\Domain\Hiring\Models\JobQuestion;

class DeleteQuestions
{
    /**
     *Delete questions without reference.
     * @param $data
     * @param $id
     *
     */
    public function execute($data, $id)
    {
        /*-whereHas to make sure we only get questions with type_id != 1
         and job_id to get current job questions-*/
        /*-Canned Questions have a type_id = 1-*/
        $typeID = 1;

        $allQuestions = JobQuestion::with('question')
            ->whereHas(
                'question',
                function ($type) use ($typeID) {
                    // Query the type_id field in jobQuestions (question method) table
                    $type->where('type_id', '<>', $typeID);
                }
            )
            ->where('job_id', $id)->get();
        foreach ($allQuestions as $jobQuestion) {
            //for each question in DB for this particular job
            if ($data['customQuestions'] == null) { //DB contains questions but in new edit all the questions have been deleted; delete all questions from DB
                $jobQuestion->delete();
                $jobQuestion->question->delete();
            } else { //Edit view contains questions
                $counter = 0;
                foreach ($data['customQuestions'] as $question) {
                    if (isset($question['id'])) {
                        if ($jobQuestion->que_id == $question['id']) {
                            ++$counter;
                        }
                    }
                }
                if ($counter == 0) { //questions exist in DB but have been removed from Edit view
                    $jobQuestion->delete();
                    $jobQuestion->question->delete();
                }
            }
        }
    }
}
