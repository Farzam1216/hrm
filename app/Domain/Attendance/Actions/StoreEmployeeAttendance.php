<?php


namespace App\Domain\Attendance\Actions;

use App\Domain\Integrations\Actions\SendPumbleWebhook;
use App\Domain\Attendance\Models\EmployeeAttendance;
use App\Domain\Attendance\Models\WorkSchedule;
use App\Domain\Employee\Models\Employee;
use Carbon\Carbon;

class StoreEmployeeAttendance
{
    /**
     * add available plans in benefitGroup
     * @param $request
     */
    public function execute($request, $id)
    {
        $employeeAttendance = EmployeeAttendance::where('employee_id', $id)
            ->whereNull('time_out')
            ->whereDate('created_at', Carbon::today())->first();

        if ($employeeAttendance == null) {
            $employeeAttendance = new EmployeeAttendance();
        }

        $employeeAttendance->employee_id = $id;
        $user = Employee::find($id);
        $workScheduleID = WorkSchedule::find($user->work_schedule_id);
        $now = Carbon::now();
        $result = false;

        switch ($request['clock_type']) {
            case 'IN':
                //first time mark attendance of the employee for the day
                if ($now->gte(Carbon::parse($workScheduleID->schedule_start_time))) {
                    if ($now->lte(Carbon::parse($workScheduleID->flex_time_in))) {
                        $employeeAttendance->time_in = $now->format('g:i A');
                        $result = true;
                        $employeeAttendance->save();
                    } else {
                        $employeeAttendance->time_in = $now->format('g:i A');
                        $employeeAttendance->time_in_status = 'Late';
                        $result = true;
                        $employeeAttendance->save();
                    }

                    (new SendPumbleWebhook)->execute('AOA @' . $user->firstname.$user->employee_no);
                }
                break;
            case "OUT":
                //Clock out for employee either in break or any other reason
                if ($now->gte(Carbon::parse($workScheduleID->schedule_break_time)) && $now->lte(Carbon::parse($workScheduleID->schedule_back_time)) && $request['clock_type'] == "OUT") {
                    $employeeAttendance->time_out = $now->format('g:i A');
                    $employeeAttendance->reason_for_leaving = 'Break Time';
                    $employeeAttendance->save();
                    $result = true;
                    (new SendPumbleWebhook)->execute('brb-break @' . $user->firstname.$user->employee_no);
                } elseif ($now->gte(Carbon::parse($workScheduleID->schedule_end_time)) && $request['clock_type'] == "OUT") {
                    $employeeAttendance->time_out = $now->format('g:i A');
                    $employeeAttendance->reason_for_leaving = 'Schedule Time Completed';
                    $employeeAttendance->save();
                    $result = true;
                    (new SendPumbleWebhook)->execute('AH @' . $user->firstname.$user->employee_no);
                } elseif ($now->lte(Carbon::parse($workScheduleID->schedule_end_time)) && $request['clock_type'] == "OUT") {

                    $validated = $request->validate([
                        'reason_for_leaving' => 'required',
                    ]);

                    $employeeAttendance->time_out = $now->format('g:i A');
                    $employeeAttendance->reason_for_leaving = $request['reason_for_leaving'];
                    $employeeAttendance->save();
                    $result = true;
                    (new SendPumbleWebhook)->execute($request['reason_for_leaving'] . ' @' . $user->firstname.$user->employee_no);
                }
                break;
            case 'Again_IN':
                //back from break
                $employeeAttendance->time_in = $now->format('g:i A');
                $result = true;
                $employeeAttendance->save();
                (new SendPumbleWebhook)->execute('back @' . $user->firstname.$user->employee_no);
                break;
        }

        return $result;
    }
}
