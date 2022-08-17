<?php

namespace App\Domain\Attendance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domain\Employee\Models\Employee;

class EmployeeAttendanceCorrection extends Model
{
    use HasFactory;

    protected $fillable = [
        'date', 'time_in', 'time_out', 'time_in_status', 'attendance_status', 'reason_for_leaving', 'status', 'total_entries', 'attendance_id', 'remove_attendance_id', 'employee_id',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
