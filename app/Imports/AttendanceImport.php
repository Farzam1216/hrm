<?php

namespace App\Imports;


use App\Domain\Attendance\Models\ImportEmployeeAttendance;
use App\Domain\Employee\Models\Employee;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Carbon;

class AttendanceImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return  new ImportEmployeeAttendance([
           $excel_data =  [
            'employee_number' => !empty($row['employee_number'])? $row['employee_number'] : null,
            'date' => !empty($row['date']) ? Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date']))->format('Y-m-d') : '',
            'time_in' => !empty($row['time_in']) ? Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['time_in']))->format('h:i A') : '',
            'time_out' => !empty($row['time_out']) ? Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['time_out']))->format('h:i A') : '',
            'reason_for_leaving'=> !empty($row['reason_for_leaving'])? $row['reason_for_leaving'] : null,
            'status' => !empty($row['status'])? $row['status'] : null,
            ],
            'excel_data' => json_encode($excel_data),
        ]);
    }
}
