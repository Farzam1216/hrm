<?php

namespace App\Domain\Benefit\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeBenefitStatusHistory extends Model
{
    public function employeeBenefitStatus()
    {
        return $this->belongsTo(EmployeeBenefitStatus::class);
    }
}
