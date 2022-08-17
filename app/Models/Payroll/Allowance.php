<?php

namespace App\Models\Payroll;

use Illuminate\Database\Eloquent\Model;

class Allowance extends Model
{
    protected $fillable = [
        'template_id',
        'allowance_name',
        'amount',
        'type'
    ];

    public function salaryTemplate()
    {
        return $this->belongsTo('App\Models\Payroll\SalaryTemplate', 'template_id');
    }
}
