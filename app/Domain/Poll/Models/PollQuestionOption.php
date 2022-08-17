<?php

namespace App\Domain\Poll\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PollQuestionOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'option_name', 'question_id', 'poll_id'
    ];

}
