<?php

namespace App\Domain\PayRoll\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollHistory extends Model
{
    use HasFactory;
    public function employee()
    {
        return $this->belongsTo('App\Domain\Employee\Models\Employee', 'employee_id');
    }
}
