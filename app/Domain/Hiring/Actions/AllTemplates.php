<?php


namespace App\Domain\Hiring\Actions;

use App\Domain\Hiring\Models\EmailTemplate;

class AllTemplates
{
    /**
     * @return EmailTemplate[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     * All Templates except for Welcome Email Template
     */
    public function execute()
    {
        $allTemplates = EmailTemplate::with('emailAttachments')->where('welcome_email', '<>', 1)->get();
        return $allTemplates;
    }
}
