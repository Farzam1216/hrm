<?php

namespace App\Domain\Employee\Models;

use App\Domain\Task\Models\TaskRequiredForFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Designation extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'designation_name','status'
    ];

    /**
     * Get the designation based filter of task template.
     */
    public function task_required_for_filter()
    {
        return $this->morphOne(TaskRequiredForFilter::class, 'filter');
    }

    public function job()
    {
        return $this->hasMany('App\Models\Job');
    }

    public function employee()
    {
        return $this->hasMany('App\Domain\Employee\Models\Employee', 'designation_id');
    }
}
