<?php

namespace App\Domain\Hiring\Actions;

use App\Domain\Hiring\Models\EmailTemplate;
use Session;

class StoreEmailTemplate
{
    public function execute($request)
    {
        $emailTemplateExist = EmailTemplate::where('template_name', $request->template_name)->first();
        $welcomeTemplate = EmailTemplate::where('welcome_email', 1)->first();
        if ($emailTemplateExist == null) {
            $emailTemplate = (new SendWelcomeMail())->execute($welcomeTemplate, $request);
            (new StoreEmailAttachments())->execute($request, $emailTemplate);
            return true;
        } else {
            Session::flash('error', trans('language.Email Template with this name already exist'));
            return false;
        }
    }
}
