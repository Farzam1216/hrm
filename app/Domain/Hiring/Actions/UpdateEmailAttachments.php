<?php

namespace App\Domain\Hiring\Actions;

use App\Domain\Hiring\Models\EmailTemplateAttachment;

class UpdateEmailAttachments
{
    public function execute($request, $emailTemplate, $id, $locale)
    {
        $i = 0;
        $remainingAttachment = EmailTemplateAttachment::where('template_id', $id)->get();
        if (isset($request->document)) {
            foreach ($request->document as $file) {
                $i = 0;
                foreach ($remainingAttachment as $emaildocument) {
                    if ($file == $emaildocument->document_file_name) {
                        $i++;
                        continue;
                    }
                }
                if ($i == 0) {
                    $emailTemplateAttachment = new EmailTemplateAttachment();
                    $filePath = time() . '.' . $file->getClientOriginalExtension();
                    $file->move('storage/email_documents/', $filePath);
                    $emailTemplateAttachment->document_name = $filePath;
                    $emailTemplateAttachment->document_file_name = $file->getClientOriginalName();
                    $emailTemplateAttachment->template_id = $emailTemplate->id;
                    $emailTemplateAttachment->save();
                }
                return true;
            }
        } else {
            return false;
        }
    }
}
