<?php


namespace App\Domain\Approval\Actions;

use App\Domain\Benefit\Models\EmployeeDependent;
use App\Domain\Employee\Models\Education;
use App\Domain\Employee\Models\Employee;

class UpdateInformationAfterApproval
{
    /**
     * @param $requestedData
     * @return mixed
     */
    public function execute($requestedData)
    {
        $data = json_decode($requestedData->requested_data, true);
        $typeName = array_keys($data);
        $previousInformation = [];
        if ($typeName[0] == 'employee') {
            $employee = Employee::find($requestedData->requested_for_id);
            foreach ($data[$typeName[0]] as $field => $value) {
                $previousInformation[$field] = $employee[$field];
                $employee[$field] = $value;
            }
            $employee->save();
            (new SendEmailNotification())->execute($requestedData, $previousInformation, 'Approved', $requestedData->comments);
        } elseif ($typeName[0] == 'employeedependent') {
            $employeeDependent = EmployeeDependent::find($data[$typeName[1]]);
            foreach ($data[$typeName[0]] as $field => $value) {
                $previousInformation[$field] = $employeeDependent[$field];
                $employeeDependent[$field] = $value;
            }
            $employeeDependent->save();
            (new SendEmailNotification())->execute($requestedData, $previousInformation, 'Approved', $requestedData->comments);
        } elseif ($typeName[0] == 'education') {
            $education = Education::find($data[$typeName[1]]);
            foreach ($data[$typeName[0]] as $field => $value) {
                $previousInformation[$field] = $education[$field];
                $education[$field] = $value;
            }
            $education->save();
            $status = (new SendEmailNotification())->execute($requestedData, $previousInformation, 'Approved', $requestedData->comments);
            return $status;
        }
    }
}
