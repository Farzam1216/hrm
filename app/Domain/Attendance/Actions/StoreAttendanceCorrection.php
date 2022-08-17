<?php

namespace App\Domain\Attendance\Actions;

use App\Domain\Attendance\Actions\StoreAttendanceCorrectionRequestNotifications;
use App\Domain\Attendance\Models\EmployeeAttendanceCorrection;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class StoreAttendanceCorrection
{
    /**
     * add available plans in benefitGroup
     * @param $request
     */
    public function execute($request)
    {
        $correction = EmployeeAttendanceCorrection::where('date', $request->date)->where('employee_id', $request->employee_id)->first();

        if (!$correction || $correction->status == 'rejected') {
            $correction = new EmployeeAttendanceCorrection();
            $correction->date = $request->date;

            if (isset($request->time_in)) {
                foreach ($request->time_in as $time_in) {
                    if (!$correction->time_in) {
                        if ($time_in != null) {
                            $correction->time_in = Carbon::parse($time_in)->format('h:i A');
                        } else {
                            $correction->time_in = ' ';
                        }
                    } else {
                        if ($time_in != null) {
                            $correction->time_in = $correction->time_in.','.Carbon::parse($time_in)->format('h:i A');
                        } else {
                            $correction->time_in = $correction->time_in.','.' ';
                        }
                        
                    }
                }
            }

            if (isset($request->time_out)) {
                foreach ($request->time_out as $time_out) {
                    if (!$correction->time_out) {
                        if ($time_out != null) {
                            $correction->time_out = Carbon::parse($time_out)->format('h:i A');
                        } else {
                            $correction->time_out = ' ';
                        }
                    } else {
                        if ($time_out != null) {
                            $correction->time_out = $correction->time_out.','.Carbon::parse($time_out)->format('h:i A');
                        } else {
                            $correction->time_out = $correction->time_out.','.' ';
                        }
                        
                    }
                }
            }

            if (isset($request->time_in_status)) {
                foreach ($request->time_in_status as $time_in_status) {
                    if (!$correction->time_in_status) {
                        if ($time_in_status != null) {
                            $correction->time_in_status = $time_in_status;
                        } else {
                            $correction->time_in_status = ' ';
                        }
                    } else {
                        if ($time_in_status != null) {
                            $correction->time_in_status = $correction->time_in_status.','.$time_in_status;
                        } else {
                            $correction->time_in_status = $correction->time_in_status.','.' ';
                        }
                        
                    }
                }
            }

            if (isset($request->attendance_status)) {
                foreach ($request->attendance_status as $attendance_status) {
                    if (!$correction->attendance_status) {
                        if ($attendance_status != null) {
                            $correction->attendance_status = $attendance_status;
                        } else {
                            $correction->attendance_status = ' ';
                        }
                    } else {
                        if ($attendance_status != null) {
                            $correction->attendance_status = $correction->attendance_status.','.$attendance_status;
                        } else {
                            $correction->attendance_status = $correction->attendance_status.','.' ';
                        }
                        
                    }
                }
            }

            if (isset($request->reason_for_leaving)) {
                foreach ($request->reason_for_leaving as $reason_for_leaving) {
                    if (!$correction->reason_for_leaving) {
                        if ($reason_for_leaving != null) {
                            $correction->reason_for_leaving = $reason_for_leaving;
                        } else {
                            $correction->reason_for_leaving = ' ';
                        }
                    } else {
                        if ($reason_for_leaving != null) {
                            $correction->reason_for_leaving = $correction->reason_for_leaving.','.$reason_for_leaving;
                        } else {
                            $correction->reason_for_leaving = $correction->reason_for_leaving.','.' ';
                        }
                        
                    }
                }
            }

            if (isset($request->attendance_id)) {
                foreach ($request->attendance_id as $attendance_id) {
                    if (!$correction->attendance_id) {
                        $correction->attendance_id = $attendance_id;
                    } else {
                        $correction->attendance_id = $correction->attendance_id.','.$attendance_id;
                    }
                }
            }
            if (isset($request->remove_attendance_id)) {
                foreach ($request->remove_attendance_id as $remove_attendance_id) {
                    if (!$correction->remove_attendance_id) {
                        $correction->remove_attendance_id = $remove_attendance_id;
                    } else {
                        $correction->remove_attendance_id = $correction->remove_attendance_id.','.$remove_attendance_id;
                    }
                }
            }

            if (isset($request->total_entries)) {
                $correction->total_entries = $request->total_entries;
            }

            $correction->employee_id = $request->employee_id;
            $correction->save();

            (new StoreAttendanceCorrectionRequestNotifications())->execute($request->employee_id);

            return $correction;
        }

        if ($correction) {
            if ($correction->status == 'pending') {
                return $correction = 'exist';
            } else {
                return $correction = 'processed';
            }
        }
    }
}
