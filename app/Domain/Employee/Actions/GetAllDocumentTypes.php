<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\DocumentType;

class GetAllDocumentTypes
{
    public function execute()
    {
        return DocumentType::all();
    }
}
