<?php

namespace App\Domain\PerformanceReview\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domain\PerformanceReview\Models\PerformanceQuestion;

class PerformanceQuestionnaireAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
    	'questionnaire_id', 'question_id', 'answer', 'option_id',
    ];

    public function questions() 
    {
        return $this->belongsTo(PerformanceQuestion::class, 'question_id', 'id');
    }
}
