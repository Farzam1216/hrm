<?php


namespace App\Domain\Hiring\Actions;

use App\Domain\Hiring\Models\EmailTemplate;


class GetEmailTemplates
{
    /**
     * @param $id
     * @param $data
     */
    public function execute()
    {
        return EmailTemplate::all();

    }
}
