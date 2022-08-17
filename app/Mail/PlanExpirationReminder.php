<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PlanExpirationReminder extends Mailable
{
    use Queueable, SerializesModels;
    protected $admin;
    protected $plans;
    protected $benefitsPageURL;
    protected $endDate;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($admin, $plans, $benefitsPageURL, $endDate)
    {
        $this->admin = $admin;
        $this->plans= $plans;
        $this->benefitsPageURL = $benefitsPageURL;
        $this->endDate= $endDate;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('freeever80@gmail.com')
            ->with([
                'plans' => $this->plans,
                'admin' => $this->admin,
                'URL'=> $this->benefitsPageURL,
                'end_date' => $this->endDate,
            ])
            ->subject('Plan Expiration Reminder Email')->view('emails.benefit_plan_expiration_reminder_email');
    }
}
