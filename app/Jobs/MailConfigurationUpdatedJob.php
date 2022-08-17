<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailConfigurationUpdated;
use App\Domain\SmtpDetail\Actions\StoreMailConfigurationUpdatedNotification;

class MailConfigurationUpdatedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
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
    public function handle()
    {
        Mail::to($this->adminUser->official_email)->send(new MailConfigurationUpdated($this->adminUser, $this->employee));
        (new StoreMailConfigurationUpdatedNotification())->execute($this->employee);
    }
}
