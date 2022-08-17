<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\DocumentType as DocumentType;

class GetDocTypeByID
{
    public function execute($id)
    {
        return DocumentType::find($id);
    }
}