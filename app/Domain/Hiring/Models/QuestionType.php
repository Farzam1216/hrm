<?php

namespace App\Domain\Hiring\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionType extends Model
{
    //
    protected $fillable=[
        'type'   ];

    public function questions()
    {
        return $this->hasMany('App\Domain\Hiring\Models\Question', 'type_id');
    }
}
