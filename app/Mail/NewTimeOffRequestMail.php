<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewTimeOffRequestMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $user;
    protected $employeeData;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $employeeData)
    {
        $this->user = $user;
        $this->employeeData = $employeeData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.new-time-off-request')->with('user', $this->user)->with('employeeData', $this->employeeData);
    }
}
