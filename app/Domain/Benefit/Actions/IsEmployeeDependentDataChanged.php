<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\EmployeeDependent;

class IsEmployeeDependentDataChanged
{
    /**
     * @param $fields
     * @param $id
     * @return array
     */
    public function execute($data, $empID, $dependentID)
    {
        $employeeDependent = EmployeeDependent::where('employee_id', $empID)->find($dependentID)->toArray();
        $changedFields = [];
        foreach ($data as $field => $value) {
            if ($employeeDependent[$field] != $data[$field]) {
                $changedFields = array_add($changedFields, $field, $data[$field]);
            }
        }
        return $changedFields;
    }
}
