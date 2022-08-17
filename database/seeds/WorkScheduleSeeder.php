<?php

namespace Database\Seeders;

use App\Domain\Attendance\Models\WorkSchedule;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class WorkScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $workSchedule = new WorkSchedule();
        $workSchedule->title = "Morning Schedule";
        $workSchedule->schedule_start_time = "9:00 AM";
        $workSchedule->flex_time_in = '9:30 AM';
        $workSchedule->schedule_break_time = '2:00 PM';
        $workSchedule->schedule_back_time = '3:00 PM';
        $workSchedule->schedule_end_time = '6:00 PM';
        $workSchedule->schedule_hours = '8';
        $workSchedule->save();

    }
}
