<?php


namespace App\Domain\Hiring\Models;

use Illuminate\Database\Eloquent\Model;

class CandidateStatus extends Model
{
    //
    protected $fillable = [
        'candidate_id', 'change_in', 'status', 'set_by',
    ];


    public function candidate()
    {
        return $this->belongsTo('App\Domain\Hiring\Models\Candidate', 'candidate_id');
    }

    public function comment()
    {
        return $this->hasOne('App\Domain\Hiring\Models\CandidateComment', 'candidate_status_id');
    }
}
