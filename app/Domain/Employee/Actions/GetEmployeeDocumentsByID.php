<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\EmployeeDocument as EmployeeDocument;

class GetEmployeeDocumentsByID
{
    public function execute($id)
    {
        return EmployeeDocument::find($id);
    }
}