<?php

namespace App\Domain\PerformanceReview\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function assignedQuestions()
    {
        return $this->hasMany(PerformanceFormQuestion::class, 'form_id');
    }
}
