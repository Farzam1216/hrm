<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\Note;

class GetEmployeeNotes
{
    public function execute($employee_id)
    {
        return Note::where('employee_id', $employee_id)->get();
    }
}
