<?php


namespace App\Domain\Hiring\Actions;

use App\Domain\Hiring\Models\EmailTemplateAttachment;

class UpdateEmailTemplates
{
    public function execute($locale, $request, $id)
    {
        $emailTemplate = (new EditEmailTemplate())->execute($request, $id);
        $emailTemplateAttachment = EmailTemplateAttachment::where('template_id', $id)->get();
        (new DeleteEmailAttachment())->execute($request, $emailTemplateAttachment);
        return (new UpdateEmailAttachments())->execute($request, $emailTemplate, $id, $locale);
    }
}
