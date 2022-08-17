<?php

namespace App\Domain\Attendance\Models;

use Illuminate\Database\Eloquent\Model;

class WorkSchedule extends Model
{
    protected $fillable = [
        'title',
        'schedule_start_time',
        'flex_time_in',
        'flex_time_break',
        'schedule_break_time',
        'schedule_back_time',
        'schedule_end_time',
        'schedule_hours',
        'non_working_days',
    ];
}
