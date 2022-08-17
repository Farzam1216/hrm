<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\EmployeeDocument;

class UpdateEmployeeDocument
{
    /**
     * @param $data
     * @param $id
     *
     * @return bool
     */
    public function execute($request, $doc_id)
    {
        $doc_update = EmployeeDocument::find($doc_id);
        $doc_update->doc_name = $request->name;
        $doc_update->doc_type = $request->doc_type;
        $doc_update->status = $request->status;
        if (!$request->has('file')) {
            $doc_update->doc_file = $request->previous_file;
        } else {
            $document_file = time().'_'.trim($request->file->getClientOriginalName());
            $document_file = preg_replace('/\s+/', '', $document_file);
            $request->file->move('storage/documents/', $document_file);
            $doc_update->doc_file = 'storage/documents/'.$document_file;
        }
        if ($doc_update->save()) {
            return true;
        }
        return false;
    }
}
