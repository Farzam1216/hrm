<?php


namespace App\Domain\Hiring\Actions;

use App\Domain\Hiring\Models\EmailTemplateAttachment;

class DeleteEmailAttachment
{
    public function execute($request, $emailTemplateAttachment)
    {
        $count = 0;
        foreach ($emailTemplateAttachment as $emaildocument) {
            $count = 0;
            if ($request->document) {
                foreach ($request->document as $file) {
                    if ($file == $emaildocument->document_file_name) {
                        $count++;
                    }
                }
            }
            if ($count == 0) {
                $email = EmailTemplateAttachment::find($emaildocument->id);
                $email->delete();
            }
        }
    }
}
