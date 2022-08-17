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

class GetPreviousData
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
     * get $previousData data of all fields
     * @param $requestData, $employeeID
     *
     */
    public function execute($requestData, $employeeID)
    {
        $result = [];
        foreach ($requestData as $Model => $fields) {
            foreach ($fields as $dbName => $valueData) {
                if ($valueData[0]['id'] != null) {
                    $previousField = $this->allModels[$Model]::where('id', $valueData[0]['id'])->first([$dbName])->toArray();
                    $result[$dbName] = $previousField[$dbName];
                }
            }
        }
        return $result;
    }
}
