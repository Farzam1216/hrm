<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use App\Domain\Employee\Models\Employee;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use App\Notifications\DefaultApprovalNotification;

class ApproveTimeOffJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $requesterEmployee;
    protected $notificationDetail;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($requesterEmployee, $notificationDetail)
    {
        //
        $this->requesterEmployee = $requesterEmployee;
        $this->notificationDetail = $notificationDetail;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $user = (new Employee)->forceFill([
            'id' => $this->requesterEmployee->id, 'name' => $this->requesterEmployee->firstname, 'email' => $this->requesterEmployee->official_email,
        ]);
        $user->notify(new DefaultApprovalNotification($this->requesterEmployee, $this->notificationDetail));
    }
}
