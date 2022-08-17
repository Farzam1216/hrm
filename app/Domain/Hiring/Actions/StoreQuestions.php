<?php


namespace App\Domain\Hiring\Actions;

use App\Domain\Hiring\Models\Question;

class StoreQuestions
{
    /**
     * @param $data
     * @param $id
     * Store Custom Questions in  Questions Table
     */
    public function execute($data, $id)
    {
        $count = 0;
        $customdata = [];
        if ($data['customQuestions'] != null) {
            foreach ($data['customQuestions'] as $value) {
                if (isset($value['status'])) {
                    $status = 1;
                } else {
                    $status = 0;
                }
                $customdata[$count]['status'] = $status;
                $questionID = Question::create(
                    [
                        'fieldType' => 'text',
                        'question' => $value['question'],
                        'type_id' => 2,
                    ]
                );
                $customdata[$count]['id'] = $questionID->id;
                $customdata[$count]['question'] = $value['question'];
                $count++;
            }
        }
        (new InsertCustomQuestionStatus())->execute($id, $customdata);
    }
}
