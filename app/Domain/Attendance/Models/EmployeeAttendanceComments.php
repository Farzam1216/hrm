<?php

namespace App\Domain\Attendance\Models;

use App\Domain\Employee\Models\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeAttendanceComments extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_attendance_id',
        'comment',
        'comment_added_by',
    ];

    public function employeeAttendance()
    {
        return $this->belongsTo(EmployeeAttendance::class,'employee_attendance_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class,'comment_added_by');
    }
}
