<?php


namespace App\Domain\Task\Actions;

use App\Domain\Task\Models\TaskAttachmentTemplate;

class UpdateTaskDocument
{
    /**
     * @param $data
     * @param $id
     */
    public function execute($data, $id)
    {
        if (isset($data['optionalDocument'])) {
            foreach ($data['optionalDocument'] as $file) {
                $taskDocument = new TaskAttachmentTemplate();
                $filePath = time() . '.' . $file['document']->getClientOriginalExtension();
                $file['document']->move(public_path('documents'), $filePath);
                $taskDocument->document_name = $filePath;
                $taskDocument->document_file_name = $file['document']->getClientOriginalName();
                $taskDocument->task_id = $id;
                $taskDocument->save();
            }
        }
    }
}
