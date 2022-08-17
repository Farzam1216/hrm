<?php

namespace App\Domain\Attendance\Actions;

use App\Domain\Attendance\Actions\StoreAttendanceCorrectionRequestUpdateNotifications;
use App\Domain\Attendance\Models\EmployeeAttendanceCorrection;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UpdateAttendanceCorrectionById
{
    /**
     * add available plans in benefitGroup
     * @param $request
     */
    public function execute($request, $id)
    {
        $correction = EmployeeAttendanceCorrection::where('id', $id)->first();

        if ($correction && $correction->status == 'pending') {
            if (isset($request->time_in)) {
                $time_ins = '';
                foreach ($request->time_in as $time_in) {
                    if (!$time_ins) {
                        if ($time_in != null) {
                            $time_ins = Carbon::parse($time_in)->format('h:i A');
                        } else {
                            $time_ins = ' ';
                        }
                    } else {
                        if ($time_in != null) {
                            $time_ins = $time_ins.','.Carbon::parse($time_in)->format('h:i A');
                        } else {
                            $time_ins = $time_ins.','.' ';
                        }
                        
                    }
                }
                $correction->time_in = $time_ins;
            }

            if (isset($request->time_out)) {
                $time_outs = '';
                foreach ($request->time_out as $time_out) {
                    if (!$time_outs) {
                        if ($time_out != null) {
                            $time_outs = Carbon::parse($time_out)->format('h:i A');
                        } else {
                            $time_outs = ' ';
                        }
                    } else {
                        if ($time_out != null) {
                            $time_outs = $time_outs.','.Carbon::parse($time_out)->format('h:i A');
                        } else {
                            $time_outs = $time_outs.','.' ';
                        }
                        
                    }
                }
                $correction->time_out = $time_outs;
            }

            if (isset($request->time_in_status)) {
                $time_in_statuses = '';
                foreach ($request->time_in_status as $time_in_status) {
                    if (!$time_in_statuses) {
                        if ($time_in_status != null) {
                            $time_in_statuses = $time_in_status;
                        } else {
                            $time_in_statuses = ' ';
                        }
                    } else {
                        if ($time_in_status != null) {
                            $time_in_statuses = $time_in_statuses.','.$time_in_status;
                        } else {
                            $time_in_statuses = $time_in_statuses.','.' ';
                        }
                        
                    }
                }
                $correction->time_in_status = $time_in_statuses;
            }

            if (isset($request->attendance_status)) {
                $attendance_statuses = '';
                foreach ($request->attendance_status as $attendance_status) {
                    if (!$attendance_statuses) {
                        if ($attendance_status != null) {
                            $attendance_statuses = $attendance_status;
                        } else {
                            $attendance_statuses = ' ';
                        }
                    } else {
                        if ($attendance_status != null) {
                            $attendance_statuses = $attendance_statuses.','.$attendance_status;
                        } else {
                            $attendance_statuses = $attendance_statuses.','.' ';
                        }
                        
                    }
                }
                $correction->attendance_status = $attendance_statuses;
            }

            if (isset($request->reason_for_leaving)) {
                $reason_for_leavings = '';
                foreach ($request->reason_for_leaving as $reason_for_leaving) {
                    if (!$reason_for_leavings) {
                        if ($reason_for_leaving != null) {
                            $reason_for_leavings = $reason_for_leaving;
                        } else {
                            $reason_for_leavings = ' ';
                        }
                    } else {
                        if ($reason_for_leaving != null) {
                            $reason_for_leavings = $reason_for_leavings.','.$reason_for_leaving;
                        } else {
                            $reason_for_leavings = $reason_for_leavings.','.' ';
                        }
                    }
                }
                $correction->reason_for_leaving = $reason_for_leavings;
            }

            if (isset($request->total_entries)) {
                $correction->total_entries = $request->total_entries;
            }

            $correction->save();

            (new StoreAttendanceCorrectionRequestUpdateNotifications())->execute($correction->employee_id);

            return $correction;
        }
    }
}
