<?php

namespace App\Domain\Hiring\Models;

use Illuminate\Database\Eloquent\Model;

//use Illuminate\Database\Eloquent\Relations\Pivot;



class JobQuestion extends Model
{
    /**
 * Indicates if the IDs are auto-incrementing.
 *
 * @var bool
 */

    public $incrementing = true; //necessary for pivot table if there's an auto incrementing primary key
    protected $fillable = [
    'job_id', 'que_id', 'status'];

    public function question()
    {
        return $this->belongsTo('App\Domain\Hiring\Models\Question', 'que_id');
    }

    public function answer()
    {
        return $this->hasMany('App\Domain\Hiring\Models\CandidateAnswers', 'jobQuestions_id');
    }
}
