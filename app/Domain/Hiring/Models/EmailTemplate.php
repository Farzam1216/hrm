<?php

namespace App\Domain\Hiring\Models;

use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MailTemplates\Interfaces\MailTemplateInterface;
use Spatie\MailTemplates\Models\MailTemplate;

class EmailTemplate extends MailTemplate implements MailTemplateInterface
{
    protected $fillable = [
        'mailable',
        'template_name',
        'subject',
        'message',
    ];
    //Override function from MailTemplate
    public function scopeForMailable(Builder $query, Mailable $mailable): Builder
    {
        return $query
            ->where('mailable', get_class($mailable))
            ->where('id', $mailable->getTamplateID());
    }
    //Override function from MailTemplate
    public function getHtmlTemplate(): string
    {
        return $this->message;
    }

    public function emailAttachments()
    {
        return $this->hasMany('App\Domain\Hiring\Models\EmailTemplateAttachment', 'template_id');
    }
}
