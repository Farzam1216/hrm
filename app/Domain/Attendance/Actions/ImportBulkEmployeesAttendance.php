<?php

namespace App\Domain\Attendance\Actions;

use App\Domain\Attendance\Models\ImportEmployeeAttendance;
use Validator;
use Illuminate\Support\Facades\Schema;
use App\Domain\Attendance\Models\EmployeeAttendance;
use App\Domain\Employee\Models\Employee;
use Carbon\Carbon;
use App\Domain\Attendance\Models\WorkSchedule;
use App\Domain\Attendance\Actions\ImportSingleEmployeeAttendance;
use App\Domain\Attendance\Actions\ImportMultipleEmployeeAttendance;

class ImportBulkEmployeesAttendance
{

    //Old Name:: StoreEmployeeInformation
    /**
     * @param $data
     * @return mixed
     */
    public function execute($request)
    {
        $employeesAttendance = ImportEmployeeAttendance::all();
        $count = count($employeesAttendance);
        if (!isset($request->employeeId)) {
            for($i=0; $i<$count; $i++){
                $attendanceData = json_decode($employeesAttendance[$i]['excel_data'], true);
                if (!empty($attendanceData)) {
                        $attendance = new EmployeeAttendance;
                        foreach ($request->fields as  $index => $field) {
                            $validator = Validator::make(
                                $request->fields,
                                [
                                    'employee_number'   => 'required',
                                    'date' => 'required',
                                ],
                            );
                            if ($validator->fails()) {
                                Schema::dropIfExists('import_employee_attendances');
                                return $data = ['check' => false, 'validator' => $validator, 'row_id' => ($i+1)];
                            }
                            if($field){
                                if($field != 'employee_number'){
                                    if($field == 'date')
                                    {
                                        $attendance->created_at = $attendanceData[$index];
                                    }
                                    elseif($field == 'status'){
                                        $attendance->attendance_status = $attendanceData[$index];
                                    }
                                    else{
                                        $attendance->$field = $attendanceData[$index];
                                    }
                                }
                            }
                        }
                            $employee = Employee::where('employee_no', $attendanceData['employee_number'])->first();
                            // Checking Employee
                            if (!$employee) {
                                return $data = ['employee' => false, 'row_id' => ($i+1)];
                            }

                            $workScheduleID = WorkSchedule::find($employee->work_schedule_id);
                            // Checking Work Schedule
                            if (!$workScheduleID) {
                                return $data = ['workSchedule' => false, 'row_id' => ($i+1)];
                            }
            
                            $weekDays = explode(',', $workScheduleID->non_working_days);
            
                            $day =   date('l', strtotime($attendance['date']));
                            $now = Carbon::parse($attendance['time_in']);
                            // Check status of attendance 
                                if ($now->lte(Carbon::parse($workScheduleID->flex_time_in))) {
                                    $timeInStatus = null;
                                } else {
                                    $timeInStatus = 'Late';
                                }
                            if(count($weekDays) > 1)
                            {
                                if(ucfirst($weekDays[0]) == $day || ucfirst($weekDays[1]) == $day)
                                {
                                    if($attendance['status'] == null){
                                        $attendanceStatus = "Weekend";
                                    }
                                }
                                elseif($attendance['time_in'] == null && $attendance['time_out'] == null && $attendance['status'] == null)
                                {
                                    $attendanceStatus = "Leave Without Pay";
                                }
                                elseif($attendance['time_in'] != null && $attendance['time_out'] != null && $attendance['status'] == null)
                                {
                                    $attendanceStatus = "Present";
                                }
                                else
                                {
                                    $attendanceStatus = $attendance['status'];
                                }
    
                            }
                            else
                            {
                                if(ucfirst($weekDays[0]) == $day )
                                {
                                    if($attendance['status'] == null){
                                        $attendanceStatus = "Weekend";
                                    }  
                                }
                                elseif($attendance['time_in'] == null && $attendance['time_out'] == null && $attendance['status'] == null)
                                {
                                    $attendanceStatus = "Leave Without Pay";
                                }
                                elseif($attendance['time_in'] != null && $attendance['time_out'] != null && $attendance['status'] == null)
                                {
                                    $attendanceStatus = "Present";
                                }
                                else
                                {
                                    $attendanceStatus = $attendance['status'];
                                }
                            }    
                            //  Validator Validations goes here
                        $validator = Validator::make(
                            $attendance->toArray(),
                            [
                                'time_in'   => 'required_if:'.$attendanceStatus.',Present',
                                'time_out' => 'required_if:'.$attendanceStatus.',Present',
                                'created_at'  => 'required',
                            ],
                            [
                                'time_out.after' => 'Time out should be greater than Time In'
                            ]
                        );
                            if ($validator->fails()) {
                                    Schema::dropIfExists('import_employee_attendances');
                                    return $data = ['check' => false, 'validator' => $validator, 'row_id' => ($i+1)];
                                }

                            $attendanceExist = EmployeeAttendance::where('employee_id',$employee->id)->where('created_at', $attendance->created_at)->first();
                            if ($attendanceExist) {
                                $attendance = EmployeeAttendance::where('employee_id',$employee->id)->where('created_at', $attendance->created_at)->first();
                                $attendance->attendance_status = $attendanceStatus;
                                $attendance->time_in_status = $timeInStatus;
                                $attendance->employee_id = $employee->id; 
                                $attendance->update();
                            }
                            else{ 
                                $attendance->attendance_status = $attendanceStatus;
                                $attendance->time_in_status = $timeInStatus;
                                $attendance->employee_id = $employee->id; 
                                $attendance->save();
                            }
                }
                else {
                    return $data = ['check' => false];
                }
            }
        }
        else{
            $data = (new ImportSingleEmployeeAttendance)->execute($request);
            if($data)
            {
             return $data;
            }
            else
            {
                return $data = ['check' => true, 'validator' => '']; 
            }      
        }
        return $data = ['check' => true, 'validator' => ''];    
    }
}
