<?php



namespace App\Domain\Hiring\Models;

use Illuminate\Database\Eloquent\Model;

class CandidateComment extends Model
{
    //
    protected $fillable=[
        'candidate_status_id', ' comment',
    ];
    
   
    public function status()
    {
        return $this->hasOne('App\Domain\Hiring\Models\CandidateStatus', 'candidate_status_id');
    }
}
