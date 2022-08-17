<?php

namespace App\Domain\Employee\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domain\Holiday\Models\Holiday;
use App\Domain\Employee\Models\Employee;

class EmployeeHoliday extends Model
{
    use HasFactory;

    protected $fillable = [
        'holiday_id', 'employee_id',
    ];

    public function holiday()
    {
        return $this->belongsTo(Holiday::class, 'holiday_id', 'id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
