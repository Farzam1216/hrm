<?php

namespace App\Mail;

use App\Domain\Employee\Models\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailPasswordChange extends Mailable
{
    use Queueable, SerializesModels;
    private $employee_id;
    public $locale;

    /**
     * Create a new message instance.
     *
     * @param $id
     * @param string $password
     * @param $type
     */
    public function __construct($id, $locale)
    {
        $this->employee_id = $id;
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
        $employee = Employee::find($this->employee_id);
        return $this->view('emails.email_password_change')
        ->with('employee', $employee);
    }
}
