<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SlackInvitationMail extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    public $locale;
    public $name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $data, $locale)
    {
        $data['locale'] = $locale;
        $this->name = $data['firstname'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        app()->setLocale($this->$locale);
        return $this->subject('Slack Invitation')
         ->view('emails.slackmail', ['name' => $this->name]);
    }
}
