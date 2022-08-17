<?php

namespace App\Domain\Task\Models;

use Illuminate\Database\Eloquent\Model;

class TaskAttachmentTemplate extends Model
{
    protected $fillable = [
        'document_id',
        'task_id',
    ];
}
