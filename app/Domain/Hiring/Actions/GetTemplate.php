<?php


namespace App\Domain\Hiring\Actions;

use Illuminate\Support\Facades\DB;

class GetTemplate
{
    /**
     * @param $email
     * @param $count
     * @param $candidateEmails
     */
    public function execute($email, $count, &$candidateEmails)
    {
        //If Email Template is available and not deleted
        if ($email->emailTemplate) {
            //if template is edited after email send
            if ($email->emailTemplate->updated_at > $email->created_at) {
                goto history_table;
            } else {
                $candidateEmails[$count]['subject'] = $email->emailTemplate->subject;
                $candidateEmails[$count]['message'] = $email->emailTemplate->message;
            }
        } //Email template is deleted from emailTemplate table and is available in History table
        else {
            history_table: $historyCollection = DB::table('email_template_histories')
            ->where('emailtemp_id', $email
                ->template_id)->orderBy('created_at', 'asc')
            ->get();
            if ($historyCollection) {
                foreach ($historyCollection as $templateHistory) {
                    if ($templateHistory->created_at >= $email->created_at) {
                        $candidateEmails[$count]['mailable'] = $templateHistory->mailable;
                        $candidateEmails[$count]['name'] = $templateHistory->template_name;
                        $candidateEmails[$count]['subject'] = $templateHistory->subject;
                        $candidateEmails[$count]['message'] = $templateHistory->message;
                        break;
                    }
                }
            }
        }
    }
}
