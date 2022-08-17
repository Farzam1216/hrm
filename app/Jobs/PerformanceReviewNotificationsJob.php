<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use App\Notifications\PerformanceReviewNotifications;

class PerformanceReviewNotificationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $employeeData;
    
    protected $users;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($employeeData,$users)
    {
        //
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
        //
        Notification::send($this->users, new PerformanceReviewNotifications($this->employeeData));
    }
}
