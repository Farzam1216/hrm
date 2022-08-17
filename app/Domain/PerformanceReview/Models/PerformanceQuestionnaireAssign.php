<?php

namespace App\Domain\PerformanceReview\Models;

use App\Domain\Employee\Models\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceQuestionnaireAssign extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'manager_id',
    ];

    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id', 'id');
    }
}
