<?php


namespace App\Domain\Hiring\Actions;

use App\Domain\Hiring\Models\EmailTemplate;
use App\Domain\Hiring\Models\EmailTemplateAttachment;
use App\Mail\WelcomeMail;

class AddNewTemplate
{
    /**
     * @param $request
     * @return mixed
     */
    public function execute($request)
    {
        $emailTemplate = EmailTemplate::where('id', $request->email_template)->with('emailAttachments')->first();
        if (isset($emailTemplate)) {
            if ($emailTemplate->subject != $request->subject || $emailTemplate->message != $request->message) {
                $newTemplate = new EmailTemplate();
                $newTemplate->mailable = WelcomeMail::class;
                $newTemplate->template_name = $emailTemplate->template_name;
                $newTemplate->subject = $request->subject;
                $newTemplate->message = $request->message;
                $newTemplate->welcome_email = false;
                $newTemplate->save();
                if ($emailTemplate->emailAttachments) {
                    foreach ($emailTemplate->emailAttachments as $file) {
                        $emailTemplateAttachment = new EmailTemplateAttachment();
                        $path = time() . '.' . $file->document_name;
                        $emailTemplateAttachment->document_name = $path;
                        $emailTemplateAttachment->document_file_name = $file->document_file_name;
                        //  Storage::copy('old/file.jpg', 'new/file.jpg');
                        \Storage::copy('storage/email_documents/' . $file->document_name, 'storage/email_documents/' . $path);
                        $emailTemplateAttachment->template_id = $newTemplate->id;
                        $yes = $emailTemplateAttachment->save();
                    }
                }
                return $newTemplate->id;
            }
        }
    }
}
