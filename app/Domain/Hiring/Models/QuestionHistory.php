<?php

namespace App\Domain\Hiring\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionHistory extends Model
{
    //
    protected $fillable=[
        'que_id','que_desc','que_type', 'que_field'    ];

    public function question()
    {
        return $this->belongsTo('App\Domain\Hiring\Models\Question', 'que_id');
    }
}
