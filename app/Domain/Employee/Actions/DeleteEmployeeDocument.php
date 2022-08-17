<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\EmployeeDocument;

class DeleteEmployeeDocument
{
    public function execute($request)
    {
        $doc_type = EmployeeDocument::find($request->doc_id);
        if ($doc_type->delete()) {
            return true;
        }
        return false;
    }
}
