<?php


namespace App\Domain\Approval\Actions;

use App\Domain\Benefit\Models\EmployeeDependent;
use App\Domain\Employee\Models\Asset;
use App\Domain\Employee\Models\Department;
use App\Domain\Employee\Models\Education;
use App\Domain\Employee\Models\Employee;
use App\Domain\Employee\Models\EmployeeVisa;
use App\Domain\Employee\Models\EmploymentStatus;
use App\Domain\Employee\Models\Location;
use App\Domain\Employee\Models\Note;
use App\Models\Country;

class AddStandardOrCustomApprovalType
{
    private $allModels = [
        'EmploymentStatus' => EmploymentStatus::class,
        'Employee' => Employee::class,
        'Location' => Location::class,
        'Department' => Department::class,
        'EmployeeDependent' => EmployeeDependent::class,
        'EmployeeVisa' => EmployeeVisa::class,
        'Note' => Note::class,
        'Asset' => Asset::class,
        'Country' => Country::class,
        'Education' => Education::class,
    ];
    /**
     * @param $approvalRequest
     * @return
     */
    public function execute($approvalRequest)
    {
        $newInstance = null;
        $employeeURequestedData = json_decode($approvalRequest->requested_data, true);
        foreach ($employeeURequestedData as $model => $fields) {
            foreach ($fields as $dbFieldName => $fieldsData) {
                foreach ($fieldsData as $key => $valueData) {
                    if ($model == 'Employee') {
                        $employee = Employee::find($valueData['id']);
                        $employee->$dbFieldName = $valueData['value'];
                        $employee->save();
                    } else {
                        if ($valueData['id'] != null) {
                            //update resource
                            $instance = $this->allModels[$model]::find($valueData['id']);
                            $instance->$dbFieldName = $valueData['value'];
                            $instance->employee_id = $approvalRequest->requested_for_id;
                            $instance->save();
                        } else {
                            //New entry in database
                            $instance = new $this->allModels[$model];
                            $instance->$dbFieldName = $valueData['value'];
                            $instance->employee_id = $approvalRequest->requested_for_id;
                            $instance->save();
                            $valueData['id'] = $instance->id;
                        }
                    }
                }
            }
        }
    }
}
