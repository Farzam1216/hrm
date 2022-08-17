<?php


namespace App\Domain\Hiring\Actions;

use App\Domain\Hiring\Models\EmailTemplate;
use App\Mail\WelcomeMail;

class SendWelcomeMail
{
    public function execute($welcomeTemplate, $request)
    {
        $emailTemplate = new EmailTemplate();
        $emailTemplate->mailable = WelcomeMail::class;
        $emailTemplate->template_name = $request->template_name;
        $emailTemplate->subject = $request->subject;
        $emailTemplate->message = $request->message;
        if ($welcomeTemplate == null) {
            $emailTemplate->welcome_email = true;
            $welcomeTemplate = 1;
        } else {
            $emailTemplate->welcome_email = false;
            $welcomeTemplate = 0;
        }
        $emailTemplate->save();
        return $emailTemplate;
    }
}
