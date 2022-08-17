<?php

namespace App\Domain\Hiring\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CandidateAnswers extends Model
{
    //
    use SoftDeletes;
    protected $fillable=[
        'jobQuestions_id', 'candidate_id', 'answer'
    ];
    protected $dates = ['deleted_at'];

    public function jobQuestion()
    {
        return $this->belongsTo('App\Domain\Hiring\Models\JobQuestion', 'jobQuestions_id');
    }
    public function candidate()
    {
        return $this->belongsTo('App\Domain\Hiring\Models\Candidate', 'candidate_id');
    }
}
