<?php

namespace App\Http\Controllers\Api;

use App\Domain\Employee\Models\Employee;
use App\Http\Controllers\Controller;
use App\Models\AttendanceSummary;
use App\Traits\ApiResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     * @return mixed
     */
    public function index()
    {
        $attendance_summaries = AttendanceSummary::where('date', '!=', '')->get();
        foreach ($attendance_summaries as $key => $attendance_summary) {
            $employee = Employee::where('id', $attendance_summary->employee_id)->get();
            $attendance_summary['employee_name'] = $employee[0]->firstname;
        }
        if (!$attendance_summaries->isEmpty()) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Employee has been Received.";
            $this->responseData['data'] = $attendance_summaries;
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
        $attendance_summaries = AttendanceSummary::where('date', '!=', '')->where('employee_id', $id)->get();
        foreach ($attendance_summaries as $key => $attendance_summary) {
            $employee = Employee::where('id', $attendance_summary->employee_id)->get();
            $attendance_summary['employee_name'] = $employee[0]->firstname;
        }
        if (!$attendance_summaries->isEmpty()) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Employee Attendence has been Received.";
            $this->responseData['data'] = $attendance_summaries;
            $this->status = 200;
        }
        return $this->apiResponse();
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'employee_id' => 'required',
            'status' => 'required',
            'date' => 'required',
        ]);
        $totaltime = 0;
        if (!isset($request->is_delay)) {
            $is_delay = 'no';
        } else {
            $is_delay = $request->is_delay;
        }
        if (!empty($request->time_in)) {
            $in = Carbon::parse($request->time_in);
        }
        if (!empty($request->time_out)) {
            $out = Carbon::parse($request->time_out);
            $totaltime = $out->diffInMinutes($in);
        } else {
            $out = null;
            $totaltime = 0;
        }
        $attendance = AttendanceSummary::create([
            'employee_id' => $request->employee_id,
            'first_timestamp_in' => $request->time_in,
            'last_timestamp_out' => $request->time_out,
            'total_time' => $totaltime,
            'date' => $request->date,
            'status' => $request->status,
            'is_delay' => $is_delay,
        ]);
        if ($attendance) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Employee Attendance has been Added.";
            $this->responseData['data'] = $attendance;
            $this->status = 200;
        }
        return $this->apiResponse();
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
            'status' => 'required',
            'date' => 'required',
        ]);
        $totaltime = 0;
        if (!isset($request->is_delay)) {
            $is_delay = 'no';
        } else {
            $is_delay = $request->is_delay;
        }
        if (!empty($request->time_in)) {
            $in = Carbon::parse($request->time_in);
        }
        if (!empty($request->time_out)) {
            $out = Carbon::parse($request->time_out);
            $totaltime = $out->diffInMinutes($in);
        } else {
            $out = null;
            $totaltime = 0;
        }
        $data = [
            'employee_id' => $id,
            'first_timestamp_in' => $request->time_in,
            'last_timestamp_out' => $request->time_out,
            'total_time' => $totaltime,
            'date' => $request->date,
            'status' => $request->status,
            'is_delay' => $is_delay,
        ];
        AttendanceSummary::where('employee_id', $id)->where('date', $request->date)->update($data);
        $attendence = AttendanceSummary::where('employee_id', $id)->where('date', $request->date)->get();
        if ($attendence) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Employee Attendence has been Updated.";
            $this->responseData['data'] = $attendence;
            $this->status = 200;
        }
        return $this->apiResponse();
    }

    /**
     * Remove the specified resource from storage.
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function destroy($id, Request $request)
    {
        $this->validate($request, [
            'date' => 'required',
        ]);
        $attendance = DB::table('attendance_summaries')->where([
            'employee_id' => $id,
            'date' => $request->date,
        ])->delete();
        if ($attendance) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Employee Attendance has been Deleted.";
            $this->responseData['data'] = $attendance;
            $this->status = 200;
        }
        return $this->apiResponse();
    }
}
