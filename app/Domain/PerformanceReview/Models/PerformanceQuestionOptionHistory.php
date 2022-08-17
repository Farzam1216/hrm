<?php

namespace App\Domain\PerformanceReview\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceQuestionOptionHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'option_id', 'q_option', 'question_id'
    ];
}
