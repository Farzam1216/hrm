<?php


namespace App\Domain\Hiring\Actions;

use App\Domain\Hiring\Models\JobQuestion;

class UpdateCannedQuestionId
{
    /**
     * @param $id
     * @param $data
     */
    public function execute($id, $data)
    {
        $cannedQuestions = JobQuestion::where('job_id', $id)->get();
        $count = 0;
        if ($data) {
            foreach ($data as $question) {
                if ($cannedQuestions) {
                    foreach ($cannedQuestions as $jobQue) {
                        if ($jobQue->que_id == $question['id']) {
                            if ($question['question']) {
                                if (isset($question['status'])) {
                                    $status = 1;
                                } else {
                                    $status = 0;
                                }
                                $jobQue->status = $status;
                                $jobQue->save();
                                $count++;
                            }
                        }
                    }
                }
                if ($count == 0) {
                    if (isset($question['question'])) {
                        if (isset($question['status'])) {
                            $status = 1;
                        } else {
                            $status = 0;
                        }
                        JobQuestion::create(
                            [
                                'job_id' => $id,
                                'que_id' => $question['id'],
                                'status' => $status,
                            ]
                        );
                    }
                }
                $count = 0;
            }
        }
    }
}
