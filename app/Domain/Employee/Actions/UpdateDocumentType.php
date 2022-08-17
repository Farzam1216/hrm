<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\DocumentType;
use Illuminate\Support\Facades\Log;
use Session;

class UpdateDocumentType
{
    public function execute($id, $request)
    {
        try {
            $doc_type = DocumentType::find($id);
            $doc_type->doc_type_name = $request->doc_type_name;
            $doc_type->status = $request->status;
            $doc_type->save();
            Session::flash('success', trans('language.Document type is updated successfully'));
            return $doc_type;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Session::flash('error', trans('language.Failed to update document type.'));
        }
    }
}
