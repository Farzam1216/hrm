<?php

namespace App\Domain\Task\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeTaskHistory extends Model
{
    protected $fillable = [
        'task_id',
        'assigned_by',
        'assigned_to',
        'assigned_for',
        'status',
        'status_value',
        'task_completion_status',
    ];
}
