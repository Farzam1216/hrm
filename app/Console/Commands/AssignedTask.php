<?php

namespace App\Console\Commands;

use App\Domain\Employee\Models\Employee;
use App\Domain\Task\Models\EmployeeTask;
use App\Domain\Task\Models\Task;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

class AssignedTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assign:task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign ON Boarding Task To new Employee';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $employees = Employee::where('status', '1')->get();
        foreach ($employees as $employee) {
            $employeesTask = EmployeeTask::where('employee_id', $employee->id)->get();
            $totalEmployeeCount = count($employeesTask);
            if ($totalEmployeeCount == 0) {
                $tasks = Task::where('type', 0)->get();
                foreach ($tasks as $task) {
                    $employeeTaskNewAssign = new EmployeeTask();
                    $employeeTaskNewAssign->task_id = $task->id;
                    $employeesTaskStatus = EmployeeTask::where('task_id', $task->id)->first();
                    $employeeTaskNewAssign->assigned_by = Auth::user()->id;
                    $employeeTaskNewAssign->employee_id = $employee->id;
                    $employeeTaskAssignToLast = EmployeeTask::where('task_id', $task->id)->orderBy('created_at', 'desc')->first();
                    $employeeTaskNewAssign->assigned_to = $employeeTaskAssignToLast->assigned_to;
                    $employeeTaskNewAssign->status = $employeesTaskStatus->status;
                    if (isset($employeesTaskStatus->status_value)) {
                        $employeeTaskNewAssign->status_value = $employeesTaskStatus->status_value;
                    }
                    $employeeTaskNewAssign->save();
                }
            }
        }
    }
}
