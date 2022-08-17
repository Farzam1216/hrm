<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\DocumentType;
use Illuminate\Support\Facades\Log;
use Session;

class DeleteDocumentType
{
    /**
     * Delete Document Type.
     *
     * @param $id
     */
    public function execute($id)
    {
        try {
            $doc_type = DocumentType::find($id);
            $doc_type->delete();
            Session::flash('success', trans('language.Document Type is deleted successfully'));
            return true;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Session::flash('error', trans('language.Failed to delete document type.'));
        }
    }
}
