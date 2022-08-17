<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\Education;

class IsEducationRequestedDataChanged
{
    public function execute($fields, $id)
    {
        $education = Education::find($id)->toArray();
        $changedFields = [];
        foreach ($fields as $field => $value) {
            if ($education[$field] != $value) {
                $changedFields = array_add($changedFields, $field, $value);
            }
        }
        return $changedFields;
    }
}
