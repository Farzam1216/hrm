<?php


namespace App\Domain\Hiring\Actions;

use App\Domain\Hiring\Models\CandidateEmail;
use App\Domain\Hiring\Models\CandidateEmailTemplateAttachment;
use App\Domain\Hiring\Models\EmailTemplate;
use App\Domain\Hiring\Models\EmailTemplateAttachment;
use App\Mail\CandidateMail;
use Illuminate\Support\Facades\Mail;
use phpDocumentor\Reflection\Types\Null_;

class WelcomeEmail
{
    /**
     * @param $data
     * @param $template_id
     */
    public function execute($data, $template_id)
    {
       
        $templateData = EmailTemplate::where('id', $template_id)->with('emailAttachments')->first();
        $emailAttachment = EmailTemplateAttachment::where('template_id', $templateData->id)->get();
        if (isset($data['template_id'])) {
            
            $candidate_email = new CandidateEmail();
            $candidate_email->can_id = $data['candidateID'];
            $candidate_email->job_id = $data['jobID'];
            $candidate_email->template_id = $data['template_id'];
            $candidate_email->email_to = $data['email'];
            $candidate_email->email_from =$data['sender']['sender-email'];
            $candidate_email->subject = $data['subject'];
            $candidate_email->message = $data['message'];
            $candidate_email->save();
            $candidate_email_id = $candidate_email->id;
            if($templateData->emailAttachments->isNotEmpty()){
                    foreach($emailAttachment as $attahments){
                        CandidateEmailTemplateAttachment::create(
                            [
                                'candidate_email_id' => $candidate_email_id,
                                'document_name' => $attahments['document_name'],
                                'document_file_name' => $attahments['document_file_name'],
                            ]
                        );
                    }
            }
        } else {
            $candidate_email = new CandidateEmail();
            $candidate_email->can_id = $data['candidateID'];
            $candidate_email->job_id = $data['jobID'];
            $candidate_email->template_id = $template_id;
            $candidate_email->email_to = $data['email'];
            $candidate_email->email_from =$data['sender']['sender-email'];
            $candidate_email->subject = $templateData['subject'];
            $candidate_email->message = $templateData['message'];
            $candidate_email->save();
            $candidate_email_id = $candidate_email->id;
            if($templateData->emailAttachments->isNotEmpty()){
                foreach($emailAttachment as $attahments){
                    CandidateEmailTemplateAttachment::create(
                        [
                            'candidate_email_id' => $candidate_email_id,
                            'document_name' => $attahments['document_name'],
                            'document_file_name' => $attahments['document_file_name'],
                        ]
                    );
                }
        }
        }
        $emailData = CandidateEmail::where('template_id', $template_id)->with('emailTemplate.emailAttachments','candidateEmailAttachments')->orderBy('created_at', 'DESC')->first();
        Mail::to($data['email'])->send(new CandidateMail($data, $emailData));
    }
}
