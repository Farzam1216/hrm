<?php

namespace App\Models\Payroll;

use Illuminate\Database\Eloquent\Model;

class Deduction extends Model
{
    protected $fillable = [
        'template_id',
        'deduction_name',
        'amount',
        'type'
    ];

    public function salaryTemplate()
    {
        return $this->belongsTo('App\Models\Payroll\SalaryTemplate', 'template_id');
    }
}
