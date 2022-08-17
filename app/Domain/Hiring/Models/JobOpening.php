<?php

namespace App\Domain\Hiring\Models;

use Illuminate\Database\Eloquent\Model;

class JobOpening extends Model
{
    protected $fillable = [
        'title', 'description', 'location_id', 'designation_id', 'department_id', 'hiring_lead_id', 'employment_status_id', 'status', 'minimum_experience',
    ];


    /* public function candidate()
     {
         return $this->belongsToMany('App\Domain\Hiring\Models\Candidate', 'candidate_jobopening', 'job_id', 'candidate_id')->withTimestamps();
     }*/
    public function candidate()
    {
        return $this->hasMany('App\Domain\Hiring\Models\Candidate', 'job_id');
    }

    public function department()
    {
        return $this->belongsTo('App\Domain\Employee\Models\Department', 'department_id');
    }

    public function designation()
    {
        return $this->belongsTo('App\Domain\Employee\Models\Designation', 'designation_id');
    }

    public function Locations()
    {
        return $this->belongsTo('App\Domain\Employee\Models\Location', 'location_id');
    }

    public function hiringLead()
    {
        return $this->belongsTo('App\Domain\Employee\Models\Employee', 'hiring_lead_id');
    }

    public function employmentStatus()
    {
        return $this->belongsTo('App\Domain\Employee\Models\EmploymentStatus', 'employment_status_id');
    }

    public function que()
    {
        return $this->belongsToMany('App\Domain\Hiring\Models\Question', 'job_questions', 'job_id', 'que_id')->as('jobquestions')->withPivot('id', 'status')->withTimestamps();
    }
}
