<?php


namespace App\Domain\Hiring\Actions;

use Session;

class SendEmail
{
    public function execute($request, $id)
    {
        $MailData['candidateID'] = $id;
        $MailData['candidateName'] = $request->candidateName;
        $MailData['candidateLastName'] = $request->candidateLastName;
        $MailData['jobID'] = $request->job_id;
        $MailData['jobTitle'] = $request->jobTitle;
        $MailData['email'] = $request->email;
        $MailData['senderJob'] = $request->senderJob;
        $MailData['message'] = $request->message;
        $MailData['subject'] = $request->subject;
        $MailData['template_id'] = $request->email_template;
        $MailData['sender'] = ['sender-email' => $request->email_sender, 'sender-name' => $request->email_sender_name];
        (new WelcomeEmail())->execute($MailData, $MailData['template_id']);
    }
}
