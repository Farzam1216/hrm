<?php

namespace App\Domain\PerformanceReview\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domain\PerformanceReview\Models\PerformanceQuestionOption;
use App\Domain\PerformanceReview\Models\PerformanceQuestionHistory;
use App\Domain\PerformanceReview\Models\PerformanceQuestionOptionHistory;

class PerformanceQuestionHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id', 'question', 'field_type', 'placement', 'status',
    ];
}
