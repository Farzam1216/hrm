<?php

namespace App\Domain\Attendance\Models;

use App\Domain\Employee\Models\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeAttendanceHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_id', 'time_in', 'time_out', 'time_in_status', 'attendance_status', 'reason_for_leaving', 'employee_id', 'changed_by_id',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'changed_by_id');
    }
}
