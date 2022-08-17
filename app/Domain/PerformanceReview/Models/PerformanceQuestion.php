<?php

namespace App\Domain\PerformanceReview\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domain\PerformanceReview\Models\PerformanceQuestionOption;
use App\Domain\PerformanceReview\Models\PerformanceQuestionHistory;
use App\Domain\PerformanceReview\Models\PerformanceQuestionOptionHistory;

class PerformanceQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'question', 'field_type', 'placement', 'status',
    ];

    public function options() {
        return $this->hasMany(PerformanceQuestionOption::class, 'question_id');
    }

    public function questionsHistory() {
        return $this->hasMany(PerformanceQuestionHistory::class, 'question_id');
    }

    public function optionsHistory() {
        return $this->hasMany(PerformanceQuestionOptionHistory::class, 'question_id');
    }
}
