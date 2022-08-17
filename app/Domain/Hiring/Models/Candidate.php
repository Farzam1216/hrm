<?php

namespace App\Domain\Hiring\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    //
    protected $fillable=[
        'name','fname','avatar','city','job_status','job_id','recruited','email',    ];
    protected $dates = ['deleted_at'];
    
    /*  public function job()
      {
          return $this->belongsToMany('App\Domain\Hiring\Models\JobOpening', 'candidate_jobopening', 'candidate_id' , 'job_id')->withTimestamps();
      }*/
    public function job()
    {
        return $this->belongsTo('App\Domain\Hiring\Models\JobOpening', 'job_id');
    }

    public function answers()
    {
        return $this->hasMany('App\Domain\Hiring\Models\CandidateAnswers');
    }
    public function status()
    {
        return $this->hasMany('App\Domain\Hiring\Models\CandidateStatus', 'candidate_id');
    }
}
