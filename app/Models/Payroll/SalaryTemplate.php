<?php

namespace App\Models\Payroll;

use Illuminate\Database\Eloquent\Model;

class SalaryTemplate extends Model
{
    protected $fillable = [
        'template_name',
        'basic_salary',
        'status'
    ];

    public function employee()
    {
        return $this->hasMany('App\Domain\Employee\Models\Employee', 'salary_template');
    }

    public function allowances()
    {
        return $this->hasMany('App\Models\Payroll\Allowance', 'template_id');
    }

    public function deduction()
    {
        return $this->hasMany('App\Models\Payroll\Deduction', 'template_id');
    }
}
