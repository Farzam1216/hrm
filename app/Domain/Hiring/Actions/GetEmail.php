<?php


namespace App\Domain\Hiring\Actions;

use App\Domain\Hiring\Models\CandidateEmail;

class GetEmail
{
    /**
     * @param $id
     * @param $job_id
     * @return array
     */
    public function execute($id, $job_id)
    {
        $candidateEmails = [];
        $emails = CandidateEmail::with('emailTemplate', 'emailTemplate.emailAttachments')
        ->where('can_id', $id)
            ->where('job_id', $job_id)
            ->orderBy('created_at', 'DESC')->get();
        if ($emails->count() != null) {
            $count = 0;
            foreach ($emails as $email) {
                $candidateEmails[$count]['to'] = $email->email_to;
                $candidateEmails[$count]['from'] = $email->email_from;
                $candidateEmails[$count]['sentAt'] = $email->created_at;
                // $candidateEmails[$count]['template']=$email->template_id;
                (new GetTemplate())->execute($email, $count, $candidateEmails);
                if ($email->emailTemplate != null) {
                    if ($email->emailTemplate->emailAttachments) {
                        $candidateEmails[$count]['attachments'] = (new GetEmailAttachments())->execute(
                            $email
                        );
                    } else {
                        $candidateEmails[$count]['attachments'] = null;
                    }
                } else {
                    $candidateEmails[$count]['attachments'] = null;
                }
                $count++;
            }
        }
        return $candidateEmails;
    }
}
