<?php

namespace App\Console\Commands;

use App\Domain\Attendance\Models\EmployeeAttendance;
use Carbon\Carbon;
use Illuminate\Console\Command;

class EmployeeClockOutAt12AM extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'employee:clockout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This corn job will mark employee clock out if the employee missed it.';

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
        $attendances = EmployeeAttendance::where('created_at', '>=', Carbon::today())->get();
        foreach ($attendances as $attendance) {
            if($attendance->time_out == null){
                $attendance->time_out = Carbon::now()->format('g:i A');
                $attendance->save();
            }
        }
    }
}
