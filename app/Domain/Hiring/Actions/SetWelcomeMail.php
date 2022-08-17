<?php


namespace App\Domain\Hiring\Actions;

use App\Domain\Hiring\Models\EmailTemplate;


class SetWelcomeMail
{
    /**
     * @param $id
     * @param $data
     */
    public function execute($request)
    {
        $emailTemplate = EmailTemplate::find($request->id);
        $emailTemplate->welcome_email = $request->welcome_email;
        $emailTemplate->save();
        $emailTemplates = EmailTemplate::where('welcome_email', 1)->where('id', '<>', $request->id)->get();
        if ($emailTemplates) {
            foreach ($emailTemplates as $et) {
                $et->welcome_email = 0;
                $et->save();
            }
        }

        return $emailTemplate;

    }
}
