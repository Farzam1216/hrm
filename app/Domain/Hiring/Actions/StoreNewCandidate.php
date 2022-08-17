<?php


namespace App\Domain\Hiring\Actions;

use App\Domain\Hiring\Models\Candidate;

class StoreNewCandidate
{
    public function execute($request)
    {
        $new_names = (new SetNewAvatar())->execute($request); //Concatenate time withAvatar names
        $new_file_name = (new UpdateFile())->execute($request);
        $data = Candidate::create(
            [
                'name' => $request->name,
                'fname' => $request->fname,
                'email' => $request->email,
                'avatar' => 'storage/uploads/applicants/image/' . $new_names['avatar'],
                'city' => $request->city,
                'job_status' => $request->job_status,
                'job_id' => $request->position,
                'recruited' => 0,
            ]
        );
        
        $candidate = $data->id;
        (new StoreAnswers())->execute($request, $candidate, $new_file_name); //Store Answers in the System
        (new AddStatus())->execute($candidate);
        $MailData['candidateID'] = $candidate;
        $MailData['candidateName'] = $request->name;
        $MailData['jobID'] = $request->position;
        $MailData['email'] = $request->email;
        $MailData['sender'] = ['sender-email' => 'noreply@example.com', 'sender-name' => 'System Generated Email'];
        $MailData['jobTitle'] = $request->jobTitle;
        $MailData['candidateLastName'] = $request->fname;
        $MailData['senderJob'] = null;
        // $MailData['subject'] = "Welcome Email";
        // $MailData['message'] = "Welcome Message";
        $email_template = (new GetEmailTemplate())->execute();
        if ($email_template) {
            (new WelcomeEmail())->execute($MailData, $email_template->id);
        }
    }
}
