<?php

namespace App\Domain\Poll\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class PollAnswer extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id', 'answer', 'poll_id', 'question_id'
    ];
}
