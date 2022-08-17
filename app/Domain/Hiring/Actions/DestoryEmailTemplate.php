<?php


namespace App\Domain\Hiring\Actions;

use App\Domain\Hiring\Models\EmailTemplate;
use App\Domain\Hiring\Models\EmailTemplateAttachment;


class DestoryEmailTemplate
{
    /**
     * @param $id
     * @param $data
     */
    public function execute($id)
    {
        $emailTemplate = EmailTemplate::find($id);
        $emailTemplateAttachment = EmailTemplateAttachment::where('template_id', $id)->get();
        if (isset($emailTemplateAttachment)) {
            foreach ($emailTemplateAttachment as $document) {
                $document->delete();
            }
        }
        $emailTemplate->delete();
        
    }
}
