<?php

namespace App\Domain\Task\Models;

use Illuminate\Database\Eloquent\Model;

class TaskHistory extends Model
{
    protected $fillable =[
        'task_id',
        'task_name',
        'task_description',
        'task_category_id',
        'task_type',
    ];
}
