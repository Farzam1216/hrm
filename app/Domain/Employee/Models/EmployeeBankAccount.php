<?php

namespace App\Domain\Employee\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeBankAccount extends Model
{
    protected $fillable = [
        'employee_id', 'account_number', 'iban', 'bank_name', 'branch'
    ];
    public function employee()
    {
        return $this->belongsTo('App\Domain\Employee\Models\Employee', 'employee_id', 'id');
    }
}
