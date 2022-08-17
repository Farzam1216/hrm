<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Domain\Employee\Models\Employee;
use App\Domain\PayRoll\Models\PayScheduleAssign;

/**
 * Class PTOTransaction
 *
 * @package App\Console\Commands
 */
class UpdateEmployeePaySchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paySchedule:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Cron Job to update employee's assigned pay schedule with respect to the effective date of employee's compensations";

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
        \Log::info("paySchedule:update is working fine!");

        $employees = Employee::with([ 'assignedPaySchedule', 'employeeCompensations' => function ($query) {
            $query->where('effective_date', '<=', Carbon::now()->format('Y-m-d'))->orderBy('effective_date', 'desc');
        }])->get();

        foreach ($employees as $employee) {
            if (isset($employee['employeeCompensations'][0])) {

                // Change assigned pay schedule of employee
                if (isset($employee->assignedPaySchedule->pay_schedule_id)) {
                    $employee->assignedPaySchedule->pay_schedule_id = $employee['employeeCompensations'][0]->pay_schedule_id;
                    $employee->assignedPaySchedule->save();
                } else {
                    $assignPaySchedule = new PayScheduleAssign();
                    $assignPaySchedule->pay_schedule_id = $employee['employeeCompensations'][0]->pay_schedule_id;
                    $assignPaySchedule->employee_id = $employee->id;
                    $assignPaySchedule->save();
                }

                // Change status of all compansations of employee to 'inactive'
                foreach ($employee['employeeCompensations'] as $compensation) {
                    $compensation->status = 'inactive';
                    $compensation->save();
                }

                // Change status of current compansation of employee to 'active'
                $employee['employeeCompensations'][0]->status = 'active';
                $employee['employeeCompensations'][0]->save();
            }
        }

        $this->info('paySchedule:update Command Run successfully!');
    }
}
