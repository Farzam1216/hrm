<?php


namespace App\Domain\Hiring\Actions;

use App\Domain\Hiring\Models\JobQuestion;
use App\Domain\Hiring\Models\Question;

class UpdateQuestions
{
    /**
     * @param $data
     * @param $id
     * Update questions in System.
     */
    public function execute($data, $id)
    {
        if ($data['customQuestions'] != null) {
            foreach ($data['customQuestions'] as $question) {
                isset($question['status']) ? $status = '1' : $status = '0';
                if (isset($question['id'])) {
                    //Edit questions which already exist in DB
                    $oldQuestion = Question::find($question['id']);
                    $oldQuestion->id = $question['id'];
                    $oldQuestion->question = $question['question'];
                    $oldQuestion->fieldType = 'text';
                    $oldQuestion->type_id = 2;
                    $oldQuestion->save();
                    $oldJobQue = JobQuestion::where('que_id', $question['id'])
                        ->first();
                    if ($oldJobQue) {
                        $oldJobQue->status = $status;
                        $oldJobQue->save();
                    }
                } else { //Add new questions in DB
                    $count = 0;
                    $customdata = [];
                    $customdata[$count]['status'] = $status;
                    $questionID = Question::create(
                        [
                            'fieldType' => 'text',
                            'question' => $question['question'],
                            'type_id' => 2,
                        ]
                    );
                    $customdata[$count]['id'] = $questionID->id;
                    $customdata[$count]['question'] = $question['question'];
                    $count++;
                    (new InsertQuestionStatus())->execute($id, $customdata);
                }
            }
        }
    }
}
