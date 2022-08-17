<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Schema;
use App\Domain\Employee\Models\Employee;
use App\Domain\Employee\Actions\AssignOnboardingTasksToNewEmployee;

class ImportEmployeesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $employees;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($employees)
    {
        $this->employees = $employees;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function handle()
    {
        $insertEmployee = Employee::insert($this->employees);
        Schema::dropIfExists('import_employees');

        (new AssignOnboardingTasksToNewEmployee())->execute();
    }
}
