<?php

namespace App\Domain\Hiring\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    //
    protected $fillable=[
        'question','fieldType','type_id',    ];


       
    public function job()
    {
        return $this->belongsToMany('App\Domain\Hiring\Models\JobOpening', 'job_questions', 'que_id', 'job_id')->as('jobquestions')->withPivot('status')->withTimestamps();
    }

    public function type()
    {
        return $this->belongsTo('App\Domain\Hiring\Models\QuestionType', 'type_id');
    }

    public function history()
    {
        return $this->hasMany('App\Domain\Hiring\Models\QuestionHistory', 'que_id');
    }

    public function jobques()
    {
        return $this->hasMany('App\Domain\Hiring\Models\JobQuestion', 'que_id');
    }
}
