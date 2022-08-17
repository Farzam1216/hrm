<?php


namespace App\Domain\Hiring\Actions;

use App\Domain\Hiring\Models\CandidateAnswers;

class StoreAnswers
{
    /**
     * @param $data
     * @param $id
     * @param $fileNames
     *
     * Store Answers in System.
     */
    public function execute($data, $id, $fileNames)
    {
        if ($data['question'] != null) {
            foreach ($data['question'] as $value) {
                if ($value['answer'] != null) {
                    if ($value['fieldType'] == "file") {
                        $index = $value['qid'];
                        CandidateAnswers::create(
                            [
                                'jobQuestions_id' => $value['qid'],
                                'candidate_id' => $id,
                                'answer' => 'storage/uploads/applicants/files/' . $fileNames[$index],
                            ]
                        );
                    } elseif ($value['fieldType'] == "text") {
                        CandidateAnswers::create(
                            [
                                'jobQuestions_id' => $value['qid'],
                                'candidate_id' => $id,
                                'answer' => $value['answer'],
                            ]
                        );
                    } else {
                        CandidateAnswers::create(
                            [
                                'jobQuestions_id' => $value['qid'],
                                'candidate_id' => $id,
                                'answer' => $value['answer'],
                            ]
                        );
                    }
                }
            }
        }
    }
}
