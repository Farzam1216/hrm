<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\DocumentType;
use App\Domain\Employee\Models\EmployeeDocument;

class GetEmployeeDocuments
{
    public function execute($employee_id)
    {
        $data['docs']= EmployeeDocument::where('employee_id', $employee_id)->get();
        $data['doc_types'] = DocumentType::all();
        return $data;
    }
}
