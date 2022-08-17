<?php

namespace App\Mail;

use App\Models\Company\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CompanyPoliciesMail extends Mailable
{
    use Queueable, SerializesModels;
    public $locale;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($locale)
    {
        $this->locale = $locale;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        app()->setLocale($this->locale);
        $attachments = Document::all();
        $email = $this->view('emails.policies');
        if ($attachments->isNotEmpty()) {
            foreach ($attachments as $attachment) {
                $filepath = public_path('uploads/files/'.$attachment->name);
                $fileParameters = [
                    'as' => $attachment->name,
                    'mime' => 'application/pdf',
                ];
                $email->attach($filepath, $fileParameters);
            }
        }

        return $email;
    }
}
