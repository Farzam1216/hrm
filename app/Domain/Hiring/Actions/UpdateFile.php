<?php


namespace App\Domain\Hiring\Actions;

class UpdateFile
{
    /**
     * @param $data
     * Set New Names for Answer Files to avoid naming conflicts.
     * @return array
     */
    public function execute($data)
    {
        $answerFiles = $data['question'];
        $new_file_names = [];

        if ($answerFiles) {
            foreach ($answerFiles as $files) {
                if ($files['fieldType'] == "file") {
                    $id = $files['qid'];
                    $link = $files['answer'];
                    $new_file_names[$id] = time() . $link->getClientOriginalName();
                    $link->move('storage/uploads/applicants/files', $new_file_names[$id]);
                }
            }
        }
        return $new_file_names;
    }
}
