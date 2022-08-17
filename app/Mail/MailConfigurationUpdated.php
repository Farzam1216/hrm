<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailConfigurationUpdated extends Mailable
{
    use Queueable, SerializesModels;

    protected $adminUser;
    protected $employee;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($adminUser, $employee)
    {
        $this->adminUser = $adminUser;
        $this->employee = $employee;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.mail_configuration_updated')->from('gleam@glowlogix.com')->with('adminUser', $this->adminUser)->with('employee', $this->employee);
    }
}
