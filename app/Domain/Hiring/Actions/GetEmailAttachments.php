<?php


namespace App\Domain\Hiring\Actions;

use Illuminate\Support\Facades\DB;

class GetEmailAttachments
{
    /**
     * @param $email
     * @return mixed
     */
    public function execute($email)
    {
        $emailAttachment = [];
        $count = 0;
        if ($email->emailTemplate->emailAttachments != null) {
            foreach ($email->emailTemplate->emailAttachments as $attachment) {
                if ($attachment->created_at < $email->created_at) {
                    $emailAttachment[$count]['document_name'] = $attachment->document_name;
                    $emailAttachment[$count]['document_file_name'] = $attachment->document_file_name;
                    $count++;
                }
            }
        }
        $historyCollection = DB::table('email_template_attachment_histories')
        ->where('template_id', $email->template_id)
            ->orderBy('created_at', 'asc')
            ->get();
        if ($historyCollection) {
            foreach ($historyCollection as $attachmentHistory) {
                if ($attachmentHistory->created_at >= $email->created_at) {
                    $emailAttachment[$count]['document_name'] = $attachmentHistory->document_name;
                    $emailAttachment[$count]['document_file_name'] = $attachmentHistory->document_file_name;
                    $count++;
                }
            }
        }
        if (!$emailAttachment) {
            $emailAttachment = null;
        }
        return $emailAttachment;
    }
}
