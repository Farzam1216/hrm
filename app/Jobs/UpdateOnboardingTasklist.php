<?php

namespace App\Jobs;

use App\Domain\Task\Models\EmployeeTask;
use App\Domain\Task\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Domain\Employee\Models\EmployeeJob;

class UpdateOnboardingTasklist implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $task_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($task_id)
    {
        $this->task_id = $task_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $employeeTasks=EmployeeTask::where('task_id', $this->task_id)->get();
        $task=Task::find($this->task_id);
        foreach($employeeTasks as $employeeTask)
        {
            //check if task is still applicable to the employee
            //get employees latest job details
            $latestJob=EmployeeJob::where('employee_id', $employeeTask->employee_id)->get()->sortBy('effective_date', 'desc')->first();
            if($latestJob)
            {
                //full checks if employee' task can be updated

            }

        }
    }
}
