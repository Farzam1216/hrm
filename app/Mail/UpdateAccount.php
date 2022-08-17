<?php

namespace App\Mail;

use App\Domain\Employee\Models\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UpdateAccount extends Mailable
{
    use Queueable, SerializesModels;
    private $employee_id;
    private $password;
    public $locale;

    /**
     * Create a new message instance.
     *
     * @param $id
     * @param string $password
     * @param $type
     */
    public function __construct($id, $password = '', $locale)
    {
        $this->employee_id = $id;
        $this->password = $password;
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
        return $this->view('emails.updateaccount')
            ->with('employee', $employee)->with('password', $this->password);
    }
}
