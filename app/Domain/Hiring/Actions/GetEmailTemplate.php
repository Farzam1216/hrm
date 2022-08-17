<?php


namespace App\Domain\Hiring\Actions;

use App\Domain\Hiring\Models\EmailTemplate;

class GetEmailTemplate
{
    public function execute()
    {
        $emailTemplate = EmailTemplate::where('welcome_email', 1)->first();
        return $emailTemplate;
    }
}
