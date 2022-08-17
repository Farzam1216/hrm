<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewTimeOffRequestMail;
use App\Notifications\TimeOffNotifications;

class RequestTimeOffJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $employeeData;
    protected $users;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($employeeData, $users)
    {
        $this->employeeData = $employeeData;
        $this->users = $users;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Notification::send($this->users, new TimeOffNotifications($this->employeeData));

        foreach ($this->users as $user) {
            Mail::to($user->official_email)->send(new NewTimeOffRequestMail($user, $this->employeeData));
        }
    }
}
