<?php

namespace App\Domain\PayRoll\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domain\Employee\Models\Employee;
use App\Domain\PayRoll\Models\PaySchedule;

class PayScheduleAssign extends Model
{
    use HasFactory;

    protected $fillable = [
        'pay_schedule_id', 'employee_id'
    ];

    public function employee(){
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function paySchedule(){
        return $this->belongsTo(PaySchedule::class, 'pay_schedule_id', 'id');
    }
}
