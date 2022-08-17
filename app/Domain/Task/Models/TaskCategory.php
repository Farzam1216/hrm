<?php

namespace App\Domain\Task\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskCategory extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'task_category_name', 'type'
    ];

    public function tasks()
    {
        return $this->hasMany('App\Domain\Task\Models\Task', 'category');
    }
}
