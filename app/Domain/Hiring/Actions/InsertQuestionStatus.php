<?php


namespace App\Domain\Hiring\Actions;

use App\Domain\Hiring\Models\JobQuestion;

class InsertQuestionStatus
{
    /**
     * @param $id
     * @param $data
     */
    public function execute($id, $data)
    {
        if (isset($data)) {
            foreach ($data as $Question) {
                if (isset($Question['question'])) {
                    //to deal with the scenario where user check status but doesn't check question checkbox
                    if (isset($Question['status'])) {
                        $status = 1;
                    } else {
                        $status = 0;
                    }
                    JobQuestion::create(
                        [
                            'job_id' => $id,
                            'que_id' => $Question['id'],
                            'status' => $status,
                        ]
                    );
                }
            }
        }
    }
}
