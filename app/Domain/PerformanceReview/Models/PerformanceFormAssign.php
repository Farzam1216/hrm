<?php

namespace App\Domain\PerformanceReview\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceFormAssign extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id', 'employee_id',
    ];

    public function form()
    {
        return $this->belongsTo(PerformanceForm::class, 'form_id', 'id');
    }

    public function assignedQuestions()
    {
        return $this->hasMany(PerformanceFormQuestion::class, 'form_id', 'form_id');
    }
}
