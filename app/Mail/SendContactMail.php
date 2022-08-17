<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendContactMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        //
        $this->data=$data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail =  $this->markdown('emails.sendContactMail')
            ->from($this->data['email'])
            ->with([ 
                'firstname' => $this->data['first_name'],
                'lastname' => $this->data['last_name'],
                'message' => $this->data['message'],
                'subject' => $this->data['subject']
            
            ])
            ->subject($this->data['subject']);

            if (isset($this->data['contactUsAttachments'])) {
                foreach ($this->data['contactUsAttachments'] as $file) {
                    $path=asset('storage/contact_us_attachments/'.$file['file_path']);
                    $mail->attach($path, ['as'=> $file['file_name']]);                    
                }
            }else{
                return $mail;
            }
    }
}
