<?php


namespace App\Domain\Employee\Actions;

class EditDepartment
{
    public function execute($id)
    {
        $data['department']=(new GetDepartmentByID())->execute($id);
        return $data;
    }
}