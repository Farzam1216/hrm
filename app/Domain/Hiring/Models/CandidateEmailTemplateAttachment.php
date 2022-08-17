<?php

namespace App\Domain\Hiring\Models;

use Illuminate\Database\Eloquent\Model;

class CandidateEmailTemplateAttachment extends Model
{
    protected $fillable = [
        'document_name',
        'document_file_name',
        'candidate_email_id',
    ];
    
    public function candidateEmailAttachments()
    {
        return $this->belongsTo('App\Domain\Hiring\Models\CandidateEmail', 'candidate_email_id');
    }
}
