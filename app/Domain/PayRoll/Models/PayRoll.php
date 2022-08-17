<?php

namespace App\Domain\PayRoll\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayRoll extends Model
{
    use HasFactory;

    protected $fillable = [
        'basic_salary', 'home_allowance', 'travel_expanse', 'income_tax', 'bonus', 'deduction', 'net_payable', 'month_year', 'status', 'reason', 'employee_id',
    ];
    public function employee()
    {
        return $this->belongsTo('App\Domain\Employee\Models\Employee', 'employee_id');
    }
}
