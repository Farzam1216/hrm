<?php

namespace App\Domain\Benefit\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeBenefitStatus extends Model
{
    protected $fillable = ['employee_benefit_id', 'effective_date', 'enrollment_status', 'enrollment_status_tracking_field'];

    public function employeeBenefit()
    {
        return $this->belongsTo(EmployeeBenefit::class, 'employee_benefit_id');
    }

    public function employeeBenefitHistories()
    {
        return $this->hasMany(EmployeeBenefitStatusHistory::class);
    }
}
