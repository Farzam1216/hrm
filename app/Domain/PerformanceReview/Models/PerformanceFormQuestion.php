<?php

namespace App\Domain\PerformanceReview\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceFormQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id', 'question_id',
    ];

    public function question()
    {
        return $this->belongsTo(PerformanceQuestion::class, 'question_id', 'id');
    }
}
