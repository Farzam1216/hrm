<?php


namespace App\Domain\Hiring\Actions;

use App\Domain\Hiring\Models\EmailTemplate;
use App\Domain\Hiring\Models\EmailTemplateAttachment;



class EditEmailTemplateAndAttachments
{

    public function execute($id)
    {
        $data['emailTemplate'] = EmailTemplate::find($id);
        $data['emailTemplateAttachment'] = EmailTemplateAttachment::where('template_id', $id)->get();
        return $data;
    }
}
