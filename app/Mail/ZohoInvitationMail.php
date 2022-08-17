<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ZohoInvitationMail extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    public $locale;
    public $org_email;
    public $password;
    public $getPassword;
    public $fname;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $data, $getPassword, $locale)
    {
        $data['password'] = $getPassword;
        $data['locale'] = $locale;
        $this->fname = $data['firstname'];
        $this->password = $data['password'];
        $this->org_email = $data['org_email'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        app()->setLocale($this->$locale);
        return $this->subject('Zoho Invitation')
            ->view('emails.zohomail', ['name' => $this->fname, 'org_email' => $this->org_email, 'password' => $this->password]);
    }
}
