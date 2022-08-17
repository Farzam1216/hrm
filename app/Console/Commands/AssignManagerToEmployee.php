<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Domain\Employee\Models\Employee;
use Carbon\Carbon;

class AssignManagerToEmployee extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assign:manager';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign manager to employee according to current job';

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
     * @return int
     */
    public function handle()
    {
        $employees = Employee::with(['jobs' => function ($query) {
            $query->where('effective_date', '<=', Carbon::now()->format('Y-m-d'))->orderBy('effective_date', 'desc');
        }])->get();

        foreach ($employees as $employee) {
            if ($employee['jobs'] != '[]') {
                $employee->manager_id = $employee['jobs'][0]->report_to;
                $employee->save();
                \Log::info($employee);
            }
        }
    }
}
