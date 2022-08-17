<?php


namespace App\Domain\Employee\Actions;

class EditDocType
{
    public function execute($id)
    {
        $data['doc_types']=(new GetDocTypeByID())->execute($id);
        return $data;
    }
}