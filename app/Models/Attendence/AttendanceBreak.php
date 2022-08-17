<?php

namespace App\Models\Attendence;

use Illuminate\Database\Eloquent\Model;

class AttendanceBreak extends Model
{
    protected $fillable = [
        'employee_id', 'timestamp_break_start', 'timestamp_break_end', 'comment', 'date', 'status','timestamp_in','timestamp_out'
    ];
}
