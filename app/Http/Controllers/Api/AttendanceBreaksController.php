<?php

namespace App\Http\Controllers\Api;

use App\App\Domain\Employee\Models\Branch;
use App\App\Domain\Employee\Models\Employee;
use App\Http\Controllers\Controller;
use App\Models\AttendanceBreak;
use App\Models\AttendanceSummary;
use App\Traits\ApiResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceBreaksController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $attendanceBreak = AttendanceBreak::all();
        if (!$attendanceBreak->isEmpty()) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Attendence has been Recieved.";
            $this->responseData['data'] = $attendanceBreak;
            $this->status = 200;
        }
        return $this->apiResponse();
    }

    /**
     * Display a Specific listing of the resource.
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        $attendanceBreak = AttendanceBreak::where('employee_id', $id)->get();
        if (!$attendanceBreak->isEmpty()) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Attendence has been Recieved.";
            $this->responseData['data'] = $attendanceBreak;
            $this->status = 200;
        }
        return $this->apiResponse();
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'employee_id' => 'required',
            'date' => 'required',
            'break_start' => 'required',
            'break_end' => 'required',
            'comment' => 'required',
            'status' => 'required',
        ]);
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
                $this->responseData['response'] = 1;
                $this->responseData['message'] = "Attendence Break has been Added.";
                $this->responseData['data'] = $attendance;
                $this->status = 200;
                return $this->apiResponse();
            } else {
                $this->responseData['response'] = 1;
                $this->responseData['message'] = "Error while adding attendance.";
                $this->responseData['data'] = $attendance;
                $this->status = 200;
                return $this->apiResponse();
            }
        } else {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Error while adding attendance.";
            $this->responseData['data'] = null;
            $this->status = 404;
            return $this->apiResponse();
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param $id
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'employee_id' => 'required',
            'date' => 'required',
            'break_start' => 'required',
            'break_end' => 'required',
            'comment' => 'required',
            'status' => 'required',
        ]);
        if (AttendanceSummary::where('employee_id', $request->employee_id)
                ->where('date', $request->date)->first() !== null) {
            $time = Carbon::parse($request->time);

            $attendance = [
                'employee_id' => $request->employee_id,
                'date' => $request->date,
                'timestamp_break_start' => !empty($request->break_start) ? Carbon::parse($request->break_start) : '',
                'comment' => $request->comment,
            ];

            if (!empty($request->break_end)) {
                $attendance['timestamp_break_end'] = Carbon::parse($request->break_end);
            }
            $attendance = AttendanceBreak::where('id', $id)->update($attendance);

            $this->updateTotalTime($request);
            if ($attendance) {
                $this->responseData['response'] = 1;
                $this->responseData['message'] = "Attendence Break has been updated.";
                $this->responseData['data'] = $attendance;
                $this->status = 200;
                return $this->apiResponse();
            } else {
                $this->responseData['response'] = 1;
                $this->responseData['message'] = "Error while updating attendance.";
                $this->responseData['data'] = $attendance;
                $this->status = 404;
                return $this->apiResponse();
            }
        } else {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Error while updating attendance.";
            $this->responseData['data'] = null;
            $this->status = 404;
            return $this->apiResponse();
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function destroy($id, Request $request)
    {
        $attendanceBreakDelete = AttendanceBreak::where([
            'id' => $id,
            'employee_id' => $request->employee_id,
            'date' => $request->date
        ])->delete();
        $this->updateTotalTime($request);
        $this->responseData['response'] = 1;
        $this->responseData['message'] = "Attendence Break is deleted successfully.";
        $this->responseData['data'] = $attendanceBreakDelete;
        $this->status = 200;
        return $this->apiResponse();
    }

    /**
     * @param Request $request
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
        if ($employee->location_id == 0) {
            $employee->location_id = 2;
        }
        $office_location = Branch::find($employee->location_id);
        $emp_in = Carbon::parse($first_timestamp_in);
        $ofc_in = Carbon::parse($emp_in)->toDateString() . "T" . Carbon::parse($office_location->timing_start)->toTimeString();
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
}
