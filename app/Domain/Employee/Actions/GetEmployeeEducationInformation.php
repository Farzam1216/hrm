<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\Education;

class GetEmployeeEducationInformation
{
    public function execute($id)
    {
        return Education::with('EducationType', 'SecondaryLanguage')->where('employee_id', $id)->get();
    }
}
