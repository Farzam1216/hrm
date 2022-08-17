<?php

namespace App\Domain\Hiring\Models;

use Illuminate\Database\Eloquent\Model;

class CandidateEmail extends Model
{
    protected $fillable = [
        'can_id', 'job_id', 'template_id', 'email_to', 'email_from' ,'message','subject','document_file_name','document_name',
    ];

    public function candidateEmailAttachments()
    {
        return $this->hasMany('App\Domain\Hiring\Models\CandidateEmailTemplateAttachment', 'candidate_email_id');
    }
    public function emailTemplate()
    {
        return $this->belongsTo('App\Domain\Hiring\Models\EmailTemplate', 'template_id');
    }
    public function emailSender()
    {
        return $this->hasMany('App\Domain\Employee\Models\Employee', 'email_from');
    }
}
