<?php


namespace App\Domain\Employee\Actions;

class ViewEmployeeNotes
{
    public function execute($employee_id)
    {
        (new ToggleEmployeeBasedMenuItems())->execute($employee_id);
        $notes = (new GetEmployeeNotes())->execute($employee_id);
        return $notes;
    }
}
