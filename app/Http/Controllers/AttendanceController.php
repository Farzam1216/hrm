<?php

namespace App\Http\Controllers;

use App;
use App\Domain\Employee\Models\Employee;
use App\Domain\Employee\Models\Location;
use App\Domain\Employee\Models\OrganizationHierarchy;
use App\Domain\TimeOff\Models\Leave;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DateTime;
use DB;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Mail;
use Session;

class AttendanceController extends Controller
{
    /**
     * @param $lang
     * @param string  $emp_id
     * @param string  $date
     * @param Request $request
     *
     * @return Factory|View
     */
    public function createBreak($lang, $emp_id = '', $date = '', Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $request->session()->put('locale', $locale);
        $employees = Employee::all();
        $today = Carbon::now()->toDateString();
        if ($date == '') {
            $datetime = Carbon::now();
        } else {
            $datetime = Carbon::parse($date);
        }
        $date = $datetime->toDateString();

        $datetime = Carbon::now();
        $current_time = $datetime->format('h:m');
        session(['date' => $date]);
        $selected_in_out = '';
        $attendance = AttendanceBreak::where([
            'date' => $date,
            'employee_id' => $emp_id,
        ])->orderBy('timestamp_break_start', 'asc')->first();

        $attendance_summary = AttendanceSummary::where(['date' => $date, 'employee_id' => $emp_id])->first();

        $attendances = AttendanceBreak::where([
            'date' => $date,
            'employee_id' => $emp_id,
        ])->orderBy('timestamp_break_end', 'asc')->get();

        return view('admin.attendance.create_break', [
            'employees' => $employees,
            'attendances' => $attendances,
            'attendance_summary' => $attendance_summary,
            'current_date' => $date,
            'current_time' => $current_time,
            'selected_in_out' => $selected_in_out,
            'emp_id' => $emp_id,
            'today' => $today,
            'locale' => $locale,
        ]);
    }

    /**
     * Show the List Of Employees With Their Attendance .
     *
     * @param string $id For Date of which u want the list of attendance
     *
     * @return Response
     */
    public function todayTimeline($lang, $id = '', Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $request->session()->put('locale', $locale);
        if ($id == '') {
            $today = Carbon::now()->toDateString();
        } else {
            $today = ($id);
        }
        $employees = Employee::with([
            'attendanceSummary' => function ($join) use ($today) {
                $join->where('date', $today);
            },
        ], 'branch', 'leaves')->where('status', '!=', '0')->where('type', '!=', 'remote')->get();

        //Leaves Count
        $leaveDate = [];
        $periods = [];
        $leaves = Leave::join('employees', 'leaves.employee_id', '=', 'employees.id')
            ->where('employees.type', 'office')
            ->where('employees.status', '!=', '0')
            ->where('leaves.status', 'Approved')->get();
        foreach ($leaves as $leave) {
            $periods[$leave->employee_id] = CarbonPeriod::create($leave->datefrom, $leave->dateto);
        }
        foreach ($periods as $key => $period) {
            foreach ($period as $dates) {
                $leaveDate[$key][] = $dates->format('Y-m-d');
            }
        }
        $leavesCount = 0;
        $employeeLeave = [];
        foreach ($leaveDate as $key => $leave) {
            if (in_array($today, $leave)) {
                $leavesCount = $leavesCount + 1;
                $employeeLeave[$key] = $key;
            }
        }
        //leaves Count
        $present = AttendanceSummary::with('branch')
            ->join('employees', 'employees.id', '=', 'attendance_summaries.employee_id')
            ->where('employees.type', 'office')
            ->where('employees.status', '!=', '0')
            ->where('date', $today)
            ->count();
        $employeeCount = Employee::where('type', 'office')->where('status', '!=', '0')->count();
        $absent = $employeeCount - $present - $leavesCount;
        $delays = AttendanceSummary::join(
            'employees',
            'employees.id',
            '=',
            'attendance_summaries.employee_id'
        )->where('employees.type', 'office')->where(
            'employees.status',
            '!=',
            '0'
        )->where('date', $today)->where('is_delay', 'yes')->count();

        return view('admin.attendance.today_timeline', [
            'employees' => $employees,
            'today' => $today,
            'location_id' => $id,
            'present' => $present,
            'absent' => $absent,
            'delays' => $delays,
            'leavesCount' => $leavesCount,
            'employeeLeave' => $employeeLeave,
            'locale' => $locale,
        ]);
    }

    /**
     * Store a Break Details.
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws ValidationException
     */
    public function storeBreak(Request $request)
    {
        $this->validate($request, [
            'employee_id' => 'required',
            'break_start' => 'required',
            'date' => 'required',
        ]);
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $request->session()->put('locale', $locale);
        if (AttendanceSummary::where('employee_id', $request->employee_id)
            ->where('date', $request->date)->first() !== null) {
            $time = Carbon::parse($request->time);

            $attendance = [
                'employee_id' => $request->employee_id,
                'date' => $request->date,
                'time' => $time->toTimeString(),
                'timestamp_break_start' => !empty($request->break_start) ? Carbon::parse($request->break_start) : '',
                'comment' => $request->comment,
            ];

            if (!empty($request->break_end)) {
                $attendance['timestamp_break_end'] = Carbon::parse($request->break_end);
            }
            $attendance = AttendanceBreak::create($attendance);
            $this->updateTotalTime($request);
            if ($attendance) {
                Session::flash('success', trans('language.Break is created successfully'));

                return redirect()->back()->with('locale', $locale);
            } else {
                Session::flash('error', trans('language.Error while add attendance'));

                return redirect()->back()->with('locale', $locale);
            }
        } else {
            Session::flash('error', trans('language.Error while add attendance'));

            return redirect()->back()->with('locale', $locale);
        }
    }

    /**
     * Store a Checkin And Checkout Details.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function storeAttendanceSummaryToday(Request $request)
    {
        $locale = $request->session()->get('locale');
        Carbon::parse($request->time_in);
        $attendance_summary = AttendanceSummary::where([
            'employee_id' => $request->employee_id,
            'date' => $request->date,
        ])->first();

        $employee = Employee::find($request->employee_id);
        if ($employee->location_id == 0) {
            $employee->location_id = 2;
        }

        $locations = Location::find($employee->location_id);
        if (isset($locations->timing_start)) {
            $ofc_in = Carbon::parse($request->time_in)
                    ->toDateString().' '.Carbon::parse($locations->timing_start)
                    ->toTimeString();
        }
        $emp_in = Carbon::parse($request->time_in);
        $delay = $emp_in->diffInMinutes($ofc_in);
        $is_delay = 'no';
        if ($emp_in->gt($ofc_in) && $delay > 30) {
            $is_delay = 'yes';
        }

        $attendance = AttendanceBreak::where([
            'date' => $request->date,
            'employee_id' => $request->employee_id,
        ])->orderBy('timestamp_break_start', 'asc')->get();
        $totalbreaktime = 0;

        foreach ($attendance as $i => $row) {
            $in = Carbon::parse($row->timestamp_break_start);
            $out = Carbon::parse($row->timestamp_break_end);
            $totalbreaktime += $out->diffInMinutes($in);
        }

        $in = Carbon::parse($request->time_in);

        if (!empty($request->time_out)) {
            $out = Carbon::parse($request->time_out);
            $totaltime = $out->diffInMinutes($in);
            $totaltime = $totaltime - $totalbreaktime;
        } else {
            $out = null;
            $totaltime = 0;
        }

        if (isset($attendance_summary->id)) {
            $attendance_summary->first_timestamp_in = $in;
            $attendance_summary->last_timestamp_out = $out;
            $attendance_summary->total_time = $totaltime;
            $attendance_summary->date = $request->date;
            $attendance_summary->is_delay = $is_delay;
            $attendance_summary->save();
        } else {
            $arr = [
                'employee_id' => $request->employee_id,
                'first_timestamp_in' => $in,
                'last_timestamp_out' => $out,
                'total_time' => $totaltime,
                'is_delay' => $is_delay,
                'date' => $request->date,
            ];
            // dump($arr);
            $attendance_summary = AttendanceSummary::create($arr);
        }
        if ($attendance_summary) {
            Session::flash('success', trans('language.Attendance is created successfully'));

            return redirect($locale.'/attendance/today-timeline');
        } else {
            Session::flash('error', trans('language.Error while add attendance'));

            return redirect($locale.'/attendance/today-timeline')->with('locale', $locale);
        }
    }

    public function newSlackbot(Request $request)
    {
        if (isset($request->challenge)) {
            return $request->challenge;
        }
        if ($request['event']['channel'] != config('values.SlackChannel')) {
            Log::debug('Accept from Slack Attendance Channel.');

            return;
        }
        $employee = Employee::where('slack_id', $request['event']['user'])->first();
        if (!isset($employee->id)) {
            $token = config('values.SlackToken');
            $output = file_get_contents('https://slack.com/api/users.profile.get?token='.$token.'&user='.$request['event']['user']);
            $output = json_decode($output, true);
            if (!$output['ok']) {
                Log::debug('no user info found.');

                return 'no user info found.';
            }

            $employee = Employee::where('official_email', $output['profile']['email'])->first();
            $employee->slack_id = $request['event']['user'];
            $employee->save();
            Log::debug('get and save Slack Id for employee.');
        }

        $date = Carbon::createFromTimestamp($request['event_time'])->toDateString();
        $time = Carbon::createFromTimestamp($request['event_time'])->toDateTimeString();

        $text = $request['event']['text'];

        $checkInText = [
            'aoa',
            'salam',
            'slaam',
            'slam',
            'assalam-o-alaikum',
            'assalam o alaikum',
            'assalamualaikum',
            'asslam o alaikum',
            'assalamu-alaeikum',
            'morning',
            'asslam o alikum',
            'assalamu-aleikum',
            'assalamu alaikum',
            'Assalam o Alikum',
            'Asslamo Alaikum',
            'aoa.',
        ];
        $checkOutText = [
            'ah',
            'allah haffiz',
            'allah hafiz',
            'allahhafiz',
            'allah hafiz.',
            'bye',
            'allah-hafiz',
            'allah haffiz',
            'ah.',
        ];

        if (in_array(strtolower($text), $checkInText) == true) {
            $text = 'aoa';
            $attendanceSummarycheck = AttendanceSummary::where('employee_id', $employee->id)->where(
                'date',
                $date
            )->first();
            if ($attendanceSummarycheck == null) {
                $data = [
                    'employee_id' => $employee->id,
                    'first_timestamp_in' => $time,
                    'date' => $date,
                    'total_time' => 0,
                ];
                AttendanceSummary::create($data);
            }
        } elseif (strstr(strtolower($text), 'brb')) {
            $clean = ['_', '.', '-', 'brb'];
            $comment = str_replace($clean, '', $text);
            $data = [
                'employee_id' => $employee->id,
                'timestamp_break_start' => $time,
                'comment' => $comment,
                'date' => $date,
                'total_time' => 0,
            ];
            AttendanceBreak::create($data);
        } elseif (strstr(strtolower($text), 'back')) {
            $attendanceCheck = AttendanceBreak::where('employee_id', $employee->id)->orderBy(
                'timestamp_break_start',
                'desc'
            )->first();
            if ($attendanceCheck != null) {
                $attendanceCheck->timestamp_break_end = $time;
                $attendanceCheck->save();
                $request->employee_id = $employee->id;
                $request->date = $attendanceCheck->date;
                $this->updateTotalTime($request);
            }
        } elseif (in_array(strtolower($text), $checkOutText) == true) {
            $attendanceSummary = AttendanceSummary::where('employee_id', $employee->id)->orderBy(
                'date',
                'desc'
            )->first();
            $attendanceSummary->last_timestamp_out = $time;
            $request->employee_id = $employee->id;
            $request->date = $attendanceSummary->date;
            $attendanceSummary->save();
            $this->updateTotalTime($request);
        }
    }

    /**
     * Calculate Total Time Excluding Break Times.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function updateTotalTime(Request $request)
    {
        $attendance = AttendanceBreak::where([
            'date' => $request->date,
            'employee_id' => $request->employee_id,
        ])->orderBy('timestamp_break_start', 'asc')->get();

        $attendanceSummaryTime = AttendanceSummary::where(
            'employee_id',
            $request->employee_id
        )->orderBy('first_timestamp_in', 'desc')->first();
        $first_timestamp_in = $attendanceSummaryTime->first_timestamp_in;

        $totalbreaktime = 0;
        if ($attendance->count() > 0) {
            foreach ($attendance as $i => $row) {
                $in = Carbon::parse($row->timestamp_break_start);
                $out = Carbon::parse($row->timestamp_break_end);
                $totalbreaktime += $out->diffInMinutes($in);
            }
        } else {
            $totalbreaktime = 0;
        }
        $attendance_summary = AttendanceSummary::where([
            'employee_id' => $request->employee_id,
            'date' => $request->date,
        ])->first();

        $employee = Employee::find($request->employee_id);
        if ($employee->branch_id == 0) {
            $employee->branch_id = 2;
        }
        $office_location = Location::find($employee->branch_id);
        $emp_in = Carbon::parse($first_timestamp_in);
        $ofc_in = Carbon::parse($emp_in)->toDateString().'T'.Carbon::parse($office_location->timing_start)->toTimeString();
        $delay = $emp_in->diffInMinutes($ofc_in);

        $day = Carbon::parse($request->date)->format('l');
        $is_delay = 'no';
        if ($emp_in->gt($ofc_in) && $delay > 30) {
            $is_delay = 'yes';
        }
        if (($office_location->id == 1 && $day == 'Friday') ||
            ($office_location->id == 2 && $day == 'Saturday')
        ) {
            $is_delay = 'no';
        }
        if ($attendance_summary != null) {
            $in = Carbon::parse($attendance_summary->first_timestamp_in);
            if ($attendance_summary->last_timestamp_out != null) {
                $out = Carbon::parse($attendance_summary->last_timestamp_out);
                $totaltime = $out->diffInMinutes($in);
                $totaltime = $totaltime - $totalbreaktime;
            } else {
                $totaltime = 0;
            }

            if (isset($attendance_summary->id)) {
                $attendance_summary->total_time = $totaltime;
                $attendance_summary->is_delay = $is_delay;
                $attendance_summary->save();
            }
        }
    }

    /**
     * Update Breaks Details.
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws ValidationException
     */
    public function updateBreak(Request $request)
    {
        $this->validate($request, [
            'break_start' => 'required',
            'break_end' => 'required|after:time_in',
            'date' => 'required',
        ]);
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $request->session()->put('locale', $locale);
        $attendance = AttendanceBreak::where([
            'id' => $request->query('id'),
        ])->first();

        $request->employee_id = $attendance->employee_id;

        if (isset($attendance->id) != '') {
            $attendance->date = Carbon::parse($request->date);
            $attendance->timestamp_break_start = Carbon::parse($request->break_start);
            $attendance->timestamp_break_end = Carbon::parse($request->break_end);
            $attendance->comment = $request->comment;
            $attendance->save();
        }

        $this->updateTotalTime($request);
        Session::flash('success', trans('language.Attendance Break is updated successfully'));

        return redirect()->back()->with('locale', $locale);
    }

    /**
     * Delete Break Details From The Attendance.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function deleteBreakChecktime(Request $request)
    {
        $id = $request->id;

        $attendance = AttendanceBreak::where([
            'id' => $id,
        ])->first();

        if ($attendance) {
            $attendanceCount = AttendanceBreak::where([
                'employee_id' => $attendance->employee_id,
                'date' => $attendance->date,
            ])->count();

            if ($attendanceCount == 1) {
                $attendance_summary = AttendanceSummary::where([
                    'employee_id' => $attendance->employee_id,
                    'date' => $attendance->date,
                ])->first();

                $attendance_summary->delete();
            }

            $attendance->delete();
        }

        Session::flash('success', 'Attendance Break is deleted successfully');

        return redirect()->back();
    }

    /**
     * Show Authenticated User Attendance On Timeline.
     *
     * @param $lang
     * @param string  $id      is Employee ID
     * @param Request $request
     *
     * @return Response
     *
     * @throws Exception
     */
    public function authUserTimeline($lang, $id = '', Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $employees = Employee::where('status', '!=', '0')->where('type', '!=', 'remote')->orderBy('firstname')->get();
        $days = [
            'Sunday' => 0,
            'Monday' => 1,
            'Tuesday' => 2,
            'Wednesday' => 3,
            'Thursday' => 4,
            'Friday' => 5,
            'Saturday' => 6,
        ];
        if ($id != '') {
            $employeeId = $id;
            $employee = Employee::where(['id' => $id])->first();
            $attendance_summaries = AttendanceSummary::where('employee_id', $id)->get();
        } else {
            $employeeId = Auth::user()->id;
            $employee = Employee::where(['id' => Auth::user()->id])->first();
            $attendance_summaries = AttendanceSummary::where('employee_id', Auth::user()->id)->get();
        }

        $currentMonth = date('m');
        $events = [];
        $presentDate = [];
        $branchWeekend = json_decode(Location::find($employee->branch_id)->weekend);
        //For Dow
        if ($attendance_summaries->count() > 0) {
            foreach ($attendance_summaries as $key => $value) {
                if ($value->first_timestamp_in != '') {
                    $delays = '';
                    $color = '';
                    if ($value->status == 'Short Leave') {
                        $className = '#C24BFF';
                    }
                    if ($value->status === 'Full Leave') {
                        $className = 'red';
                    }
                    if ($value->status === 'Half Leave') {
                        $className = '#57BB8A';
                    }
                    if ($value->status == 'Paid Leave') {
                        $className = '#ADFF41';
                    }
                    if ($value->status == 'present') {
                        $className = 'fc-event-success';
                    }
                    if ($value->is_delay && $value->status == 'present') {
                        $className = 'fc-event-warning';
                        $delays = $value->is_delay.' delay';
                    } else {
                        $delays = '';
                    }
                    $timeIn = Carbon::parse($value->first_timestamp_in)->format('g:i A');
                    if ($value->last_timestamp_out != '') {
                        $timeOut = Carbon::parse($value->last_timestamp_out)->format('g:i A');
                    } else {
                        $timeOut = 0;
                    }
                    $total_time = round(
                        (Carbon::parse($value->last_timestamp_out)->diffInMinutes(Carbon::parse($value->first_timestamp_in))) / 60,
                        '2'
                    );
                    $events[] = [
                        'resourceId' => $value->employee_id,
                        'title' => $value->status."\n".$timeIn.' - '.$timeOut."\n".$total_time.' hrs'."\n",
                        'date' => Carbon::parse($value->date)->toDateString(),
                        'start' => Carbon::parse($value->date)->toDateString(),
                        'end' => Carbon::parse($value->date)->toDateString(),
                        'className' => $className,
                    ];
                    $presentDate[] = $value->date;
                }
            }
        }
        //Leave Days
        $leaveDate = [];
        $periods = [];
        $leaves = Leave::where('employee_id', $employee->id)->get();
        foreach ($leaves as $leave) {
            $periods[] = CarbonPeriod::create($leave->datefrom, $leave->dateto);
        }
        foreach ($periods as $period) {
            foreach ($period as $dates) {
                $leaveDate[] = $dates->format('Y-m-d');
            }
        }

        //Leave DaysEnd
        $dow = [0, 1, 2, 3, 4, 5, 6];
        foreach ($branchWeekend as $day) {
            unset($dow[$days[$day]]);
        }
        $dow = implode(',', $dow);

        $leaveCount = [];
        foreach ($leaveDate as $leavecnt) {
            if (date('m', strtotime($leavecnt)) == $currentMonth && in_array(
                Carbon::parse($leavecnt)->format('l'),
                $branchWeekend
            ) == false) {
                $leaveCount[] = $leavecnt;
            }
        }
        $JoiningDate = Employee::where('id', $employeeId)->first();
        if ($JoiningDate->joining_date == null) {
            $JoiningDate = $JoiningDate->created_at->format('Y-m-d');
        } else {
            $JoiningDate = $JoiningDate->joining_date;
        }
        $periods[] = CarbonPeriod::create($JoiningDate, Carbon::now()->toDateString());
        $absentDates = [];
        foreach ($periods as $period) {
            foreach ($period as $dates) {
                $absentDates[] = $dates->format('Y-m-d');
            }
        }

        foreach ($absentDates as $date) {
            if (!in_array($date, $presentDate) && in_array(
                Carbon::parse($date)->format('l'),
                $branchWeekend
            ) == false && in_array(Carbon::parse($date)->toDateString(), $leaveDate) == false) {
                $events[] = [
                    'title' => 'Absent   ',
                    'date' => Carbon::parse($date)->toDateString(),
                    'className' => 'fc-event-danger',
                ];
            }
        }

        //For Absent Event

        $till_date = new DateTime();
        $absent = [];
        for ($i = 1; $i <= $till_date->format('d'); ++$i) {
            $now = Carbon::now();
            $date = Carbon::parse($i.'-'.$now->month.'-'.$now->year)->toDateString();
            if (!in_array($date, $presentDate) && in_array(
                Carbon::parse($date)->format('l'),
                $branchWeekend
            ) == false && in_array(Carbon::parse($date)->toDateString(), $leaveDate) == false) {
                $absent[] = '';
            }
        }
        $AbsentCount = count($absent);
        $leave = Leave::with('leaveType')->where('employee_id', $employee->id)->get();
        foreach ($leave as $key => $value) {
            $color = '';
            if ($value->leave_type == 'Short Leave') {
                $color = '#C24BFF';
            }
            if ($value->leave_type === 'Full Leave') {
                $color = 'red';
            }
            if ($value->leave_type === 'Half Leave') {
                $color = '#57BB8A';
            }
            if ($value->leave_type == 'Paid Leave') {
                $color = '#ADFF41';
            }
            $events[] = [
                'title' => $value->leaveType->name."\n".'Status:'.$value->status,
                'date' => $value->datefrom,
                'start' => Carbon::parse($value->datefrom)->toIso8601String(),
                'end' => date('Y-m-d', strtotime($value->dateto.' +1 day')),
                'className' => 'fc-event-primary',
            ];
        }
        //Average Arrivals
        $averageArrivals = round(AttendanceSummary::where(
            'employee_id',
            '=',
            $employee->id
        )->whereRaw(
            'MONTH(date) = ?',
            [$currentMonth]
        )->select(DB::raw('first_timestamp_in'))->avg('first_timestamp_in'));
        if ($averageArrivals == null) {
            $avgarival = '00:00';
        } else {
            $avgarival = Carbon::createFromTimestampUTC($averageArrivals)->format('g:i a');
        }

        //Average Attendance
        $absent = $AbsentCount;
        $present = AttendanceSummary::where('employee_id', $employee->id)->where(
            'status',
            'present'
        )->whereRaw('MONTH(first_timestamp_in) = ?', [$currentMonth])->count();
        $totalAttendance = AttendanceSummary::where('employee_id', $employee->id)->whereRaw(
            'MONTH(date) = ?',
            [$currentMonth]
        )->count() + $absent;
        if ($totalAttendance != 0) {
            $averageAttendance = round(($present / $totalAttendance) * 100, 2);
        } else {
            $averageAttendance = 0;
        }
        //Average Hours
        $averageHours = AttendanceSummary::where(
            'employee_id',
            '=',
            $employee->id
        )->whereRaw('MONTH(first_timestamp_in) = ?', [$currentMonth])->avg('total_time') / 60;

        //Line Manager
        $linemanagers = OrganizationHierarchy::with('lineManager')->where('employee_id', $employee->id)->get();
        $events = json_encode($events);

        return view('admin.attendance.myattendance', [
            'events' => $events,
        ])->with('employeeId', $employeeId)
            ->with('employees', $employees)
            ->with('dow', $dow)
            ->with('averageHours', floor($averageHours))
            ->with('averageArrival', $avgarival)
            ->with('averageAttendance', $averageAttendance)
            ->with('linemanagers', $linemanagers)
            ->with('present', $present)
            ->with('absent', $absent)
            ->with('leaveCount', count($leaveCount))
            ->with('locale', $locale);
    }

    /**
     * Send Correction Email To HR.
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws Exception
     */
    public function correctionEmail(Request $request)
    {
        $data = [
            'name' => Auth::user()->firstname,
            'messages' => "$request->message",
            'email' => Auth::user()->official_email,
            'date' => "$request->date",
        ];
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        try {
            Mail::send('emails.attendance_correction_email', $data, function ($message) use ($request) {
                $message->to('awaid.anjum@gmail.com')->subject(trans('language.Attendance Correction Request For Date').$request->date);
                if ($request->line_manager_email != '') {
                    $message->cc($request->line_manager_email);
                }
                $message->from('noreply@glowlogix.com', Auth::user()->official_email);
            });
        } catch (Exception $e) {
            Session::flash('error', trans('language.Email Not Send Please Set Email Configuration In .env File'));
        }
        Session::flash('success', trans('language.Correction Email Sent To the HR'));

        return redirect()->back()->with('locale', $locale);
    }

    /**
     * Delete Attendance Summary With Their Breaks.
     *
     * @param string  $id      is Employee ID
     * @param Request $request is Employee ID
     *
     * @return Response
     *
     * @throws Exception
     */
    public function attendanceSummaryDelete(Request $request, $lang, $id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $request->session()->put('locale', $locale);
        $attendanceBreakDelete = AttendanceBreak::where('employee_id', $request->att_employee_id)->where(
            'date',
            $request->att_date
        )->delete();
        $attendance_summary_delete = AttendanceSummary::find($id);
        $attendance_summary_delete->delete();
        Session::flash('success', trans('language.Attendance Deleted Successfully'));

        return redirect($locale.'/attendance/today-timeline')->with('locale', $locale);
        //return redirect()->route("today_timeline");
    }

    /**
     * Show Timeline Of Attendance.
     *
     * @param $lang
     * @param int     $id      is Branch ID
     * @param Request $request
     *
     * @return Response
     */
    public function showTimeline($lang, $id = 0, Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        if ($id == 0) {
            $employees = Employee::where('type', '!=', 'remote')->where('status', '!=', '0')->get()->toJson();
        } else {
            $employees = Employee::where(['branch_id' => $id])->where('type', '!=', 'remote')->where(
                'status',
                '!=',
                '0'
            )->get()->toJson();
        }
        $attendance_summaries = AttendanceSummary::where('date', '!=', '')->get();
        $events = [];
        foreach ($attendance_summaries as $key => $value) {
            $delays = '';
            $color = '';
            if ($value->status == 'Short Leave') {
                $color = '#C24BFF';
            }
            if ($value->status === 'Full Leave') {
                $color = 'red';
            }
            if ($value->status === 'Half Leave') {
                $color = '#57BB8A';
            }
            if ($value->status == 'Paid Leave') {
                $color = '#ADFF41';
            }
            if ($value->status == 'present') {
                $color = '#00a560';
            }
            if ($value->is_delay && $value->status == 'present') {
                $color = '#43474a';
                $delays = $value->is_delay.' delay';
            } else {
                $delays = '';
            }
            $timeIn = Carbon::parse($value->first_timestamp_in)->format('g:i A');

            if ($value->last_timestamp_out != '') {
                $timeOut = Carbon::parse($value->last_timestamp_out)->format('g:i A');
            } else {
                $timeOut = 0;
            }
            $total_time = gmdate('H:i', floor(number_format(($value->total_time / 60), 2, '.', '') * 3600));
            $events[] = [
                'resourceId' => $value->employee_id,
                'title' => $value->status."\n".$timeIn.' - '.$timeOut."\n".$total_time.' hrs'."\n",
                'date' => $value->date,
                'color' => $color,
            ];
        }
        $leave = Leave::with('leaveType')->get();
        foreach ($leave as $key => $value) {
            if ($value->leaveType->name == 'Short Leave') {
                $color = '#C24BFF';
            }
            if ($value->leaveType->name == 'Full Leave') {
                $color = 'red';
            }
            if ($value->leaveType->name == 'Half Leave') {
                $color = '#57BB8A';
            }
            if ($value->leaveType->name == 'Paid Leave') {
                $color = '#ADFF41';
            }
            if ($value->leaveType->name == 'Casual Leaves') {
                $color = '#ADFF41';
            }
            $events[] = [
                'resourceId' => $value->employee_id,
                'title' => $value->leaveType->name."\n".'Reason:'.$value->reason."\n".'Status:'.$value->status,
                'date' => $value->datefrom,
                'start' => Carbon::parse($value->datefrom)->toDateString(),
                'end' => date('Y-m-d', strtotime($value->dateto.' +1 day')),
                'color' => '#ADFF41',
            ];
        }

        $events = json_encode($events);
        $office_locations = Location::all();

        return view('admin.attendance.timeline', [
            'employees' => $employees,
            'branch_id' => $id,
            'office_locations' => $office_locations,
            'events' => $events,
            'locale' => $locale,
        ]);
    }
}
