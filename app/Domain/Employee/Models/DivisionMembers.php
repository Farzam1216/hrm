<?php

namespace App\Domain\Employee\Models;

use Illuminate\Database\Eloquent\Model;

class DivisionMembers extends Model
{
    protected $fillable = [
        'employee_id','division_id',
    ];

    public function employee()
    {
        return $this->belongsto('App\Domain\Employee\Models\Employee');
    }
}
