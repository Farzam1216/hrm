<?php

namespace App\Domain\Poll\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PollQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'title', 'poll_id', 'question_type'
    ];

    public function options()
    {
        return $this->hasMany(PollQuestionOption::class, 'question_id');
    }
}
