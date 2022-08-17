<?php

namespace App\Domain\PerformanceReview\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceQuestionOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'option', 'question_id'
    ];
}
