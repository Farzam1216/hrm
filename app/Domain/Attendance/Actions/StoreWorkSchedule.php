<?php


namespace App\Domain\Attendance\Actions;


use App\Domain\Attendance\Models\WorkSchedule;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class StoreWorkSchedule
{
    /**
     * add available plans in benefitGroup
     * @param $request
     */
    public function execute($data)
    {
        $start = Carbon::parse($data['start_time']);
        $end = Carbon::parse($data['end_time']);
        $minutes = $end->diffInMinutes($start);
        $hours = $minutes / 60;

        if($start->format('A') == 'PM' && $end->format('A') == 'AM') {
            $startMinutes = $start->diffInMinutes(Carbon::parse('23:59:00'))+1;
            $endMinutes = $end->diffInMinutes(Carbon::parse('00:00:00'));
            $hours = $startMinutes / 60 + $endMinutes / 60;
        }

        $breakStart = Carbon::parse($data['break_time']);
        $breakEnd = Carbon::parse($data['back_time']);
        $minutes = $breakEnd->diffInMinutes($breakStart);
        $hoursBreak = $minutes / 60;

        if($breakStart->format('A') == 'PM' && $breakEnd->format('A') == 'AM') {
            $breakStartMinutes = $breakStart->diffInMinutes(Carbon::parse('23:59:00'))+1;
            $breakEndMinutes = $breakEnd->diffInMinutes(Carbon::parse('00:00:00'));
            $hoursBreak = $breakStartMinutes / 60 + $breakEndMinutes / 60;
        }

        $totalHourse = $hours - $hoursBreak;
        $workSchedule = new WorkSchedule();
        $workSchedule->title = $data['title'];
        $workSchedule->schedule_start_time = $data['start_time'];
        $workSchedule->flex_time_in = $data['flex_time_in'];
        $workSchedule->schedule_break_time = $data['break_time'];
        $workSchedule->schedule_back_time = $data['back_time'];
        $workSchedule->schedule_end_time = $data['end_time'];

        $days = '';
        foreach($data['non_working_days'] as $non_working_day) {
            if ($days == '') {
                $days = $non_working_day;
            } else {
                $days = $days.','.$non_working_day;
            }
        }
        $workSchedule->non_working_days = $days;
        
        $workSchedule->schedule_hours = $totalHourse;
        if($workSchedule->save()){
            return true;
        }else{
            return false;
        }
    }
}
