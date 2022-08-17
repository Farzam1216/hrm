<?php


namespace App\Domain\Hiring\Actions;

use App\Domain\Hiring\Models\EmailTemplate;
use App\Mail\WelcomeMail;

class EditEmailTemplate
{
    public function execute($request, $id)
    {
        $emailTemplate = EmailTemplate::find($id);
        $emailTemplate->mailable = WelcomeMail::class;
        $emailTemplate->template_name = $request->template_name;
        $emailTemplate->subject = $request->subject;
        $emailTemplate->message = $request->message;
        $emailTemplate->save();
        return $emailTemplate;
    }
}
