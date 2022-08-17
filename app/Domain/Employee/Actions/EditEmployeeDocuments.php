<?php


namespace App\Domain\Employee\Actions;

class EditEmployeeDocuments
{
    public function execute($id)
    {
        $data['doc_types']=(new GetEmployeeDocumentsByID())->execute($id);
        return $data;
    }
}