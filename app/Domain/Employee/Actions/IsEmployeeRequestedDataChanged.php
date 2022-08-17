<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\Employee;

class IsEmployeeRequestedDataChanged
{
    /**
     * @param $fields
     * @param $id
     * @return array
     */
    public function execute($fields, $id)
    {
        $employee = Employee::find($id)->toArray();
        $changedFields = [];
        foreach ($fields as $field => $value) {
            if ($employee[$field] !== $value) {
                $changedFields = array_add($changedFields, $field, $value);
            }
        }
        return $changedFields;
    }
}
