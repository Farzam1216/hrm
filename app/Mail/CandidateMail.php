<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CandidateMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $emailData;
    protected $customer;

    /**
     * Create a new message instance.
     *
     * @param $customer
     * @param $emailData
     */
    public function __construct($customer, $emailData)
    {
        $this->customer = $customer;
        $this->emailData= $emailData;
    }

    /**
     * method to replace placeholders content with actual data
     * @return void
     */
    public function replaceContent()
    {
        $this->emailData['message'] = str_replace(['@name', '@lastName', '@jobTitle', '@senderName','@senderDesignation'], [$this->customer['candidateName'], $this->customer['candidateLastName'], $this->customer['jobTitle'], $this->customer['sender']['sender-name'], $this->customer['senderJob']], $this->emailData['message']);
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->replaceContent();
        $mail = $this->from($this->customer['sender']['sender-email'])
            ->with([
                'content' => $this->emailData['message'],
            ])
            ->subject($this->emailData['subject'])
            ->view('emails.candidate_email');
        if ($this->emailData['emailTemplate']['emailAttachments']) {
            foreach ($this->emailData['emailTemplate']['emailAttachments'] as $file) {
                $path=asset('storage/email_documents/'.$file['document_name']);
                $mail->attach($path, ['as'=> $file['document_file_name']]) ;// attach each file
            }
        }else{
            return $mail; //Send mail
        }
        
    }
}
