<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\EmployeeDocument;
use Session;

class StoreEmployeeDocument
{
    public function execute($request, $employee_id)
    {
        $doc_exist = EmployeeDocument::where('doc_name', $request->name)->first();
        if ($doc_exist == null) {
            $document_file = time().'_'.trim($request->file->getClientOriginalName());
            $document_file = preg_replace('/\s+/', '', $document_file);
            $request->file->move('storage/documents/', $document_file);
            EmployeeDocument::create([
                'employee_id' => $employee_id,
                'doc_name' => $request->name,
                'doc_type' => $request->type,
                'doc_file' => 'storage/documents/'.$document_file,
                'status' => $request->status,
            ]);
            Session::flash('success', trans('language.Document is added successfully'));
        } else {
            Session::flash('error', trans('language.Document with this name already exist'));
        }
    }
}
