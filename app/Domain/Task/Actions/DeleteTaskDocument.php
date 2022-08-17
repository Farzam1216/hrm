<?php


namespace App\Domain\Task\Actions;

use App\Domain\Task\Models\TaskAttachmentTemplate;

class DeleteTaskDocument
{
    public function execute($taskDocumentId)
    {
        TaskAttachmentTemplate::where('id', $taskDocumentId)->delete();
    }
}
