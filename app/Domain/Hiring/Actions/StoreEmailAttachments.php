<?php


namespace App\Domain\Hiring\Actions;

use App\Domain\Hiring\Models\EmailTemplateAttachment;
class StoreEmailAttachments
{
    public function execute($request, $emailTemplate)
    {   
        if ($request->document) {
            foreach ($request->document as $file) {
                $emailTemplateAttachment = new EmailTemplateAttachment();
                $filePath = time() . '.' . $file->getClientOriginalExtension();
                $file->move('storage/email_documents/', $filePath);
                $emailTemplateAttachment->document_name = $filePath;
                $emailTemplateAttachment->document_file_name = $file->getClientOriginalName();
                $emailTemplateAttachment->template_id = $emailTemplate->id;
                $emailTemplateAttachment->save();
            }
        }
    }
}
