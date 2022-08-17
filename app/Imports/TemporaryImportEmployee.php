<?php

namespace App\Imports;

use App\Domain\Employee\Models\ImportEmployee;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Domain\Employee\Actions\AssignOnboardingTasksToNewEmployee;

class TemporaryImportEmployee implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $employee = new ImportEmployee([
            'employee_no'                       => $row['employee_number'],
            'firstname'                         => $row['first_name'],
            'lastname'                          => $row['last_name'],
            'contact_no'                        => $row['contact_number'],
            'official_email'                    => $row['official_email'],
            'personal_email'                    => $row['personal_email'],
            'nin'                               => $row['nin'],
            'gender'                            => $row['gender'],
            'marital_status'                    => $row['marital_status'],
            'emergency_contact_relationship'    => $row['emergency_contact_relationship'],
            'emergency_contact'                 => $row['emergency_contact_number'],
            'current_address'                   => $row['current_address'],
            'permanent_address'                 => $row['permanent_address'],
            'city'                              => $row['city']
        ]);
        return $employee;
    }
}
