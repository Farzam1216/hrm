<?php


namespace App\Domain\Employee\Actions;

class ViewAllDepartments{
    public function execute($department_id)
    {
        $data['department'] = (new GetAllDepartments())->execute($department_id);
     return $data;
    }
}
