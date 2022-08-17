<?php

namespace App\Console\Commands;

use App\Domain\TimeOff\Models\AssignTimeOffType as AssignTimeOff;
use App\Domain\TimeOff\Models\TimeOffType;
use Illuminate\Console\Command;

class AssignTimeOffType extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assign:timeofftype {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign Time Off Types To new Employees';


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
        $employeeID=$this->argument('id');
        $timeofftypes = TimeOffType::all();
        if ($timeofftypes->count() != null) {
            foreach ($timeofftypes as $timeofftype) {
                $assigntimeofftype=new AssignTimeOff();
                $assigntimeofftype->employee_id=$employeeID;
                $assigntimeofftype->type_id=$timeofftype->id;
                $assigntimeofftype->accrual_option='None';
                $assigntimeofftype->save();
            }
        }
    }
}
