<?php

namespace App\Domain\Employee\Models;

use App\Domain\ACL\Models\Role;
use App\Domain\Task\Models\TaskRequiredForFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EmploymentStatus extends Model
{
    protected $fillable = [
        'employment_status', 'description', 'status'
    ];

    /**
     * Get the employment status based filter of task template.
     */
    public function task_required_for_filter()
    {
        return $this->morphOne(TaskRequiredForFilter::class, 'filter');
    }

    public function employees()
    {
        return $this->hasMany('App\Domain\Employee\Models\Employee', 'employment_status_id');
    }

    public function employeeEmploymentStatus()
    {
        return $this->hasMany('App\Domain\Employee\Models\EmployeeEmploymentStatus', 'employment_status_id');
    }



}
