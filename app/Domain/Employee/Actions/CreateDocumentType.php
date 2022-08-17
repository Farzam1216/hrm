<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\DocumentType;
use Illuminate\Support\Facades\Log;
use Session;

class CreateDocumentType
{
    /**
     * @param $data
     * @return void
     *
     */
    public function execute($data)
    {
        // try {
            $doc_exist = DocumentType::where('doc_type_name', $data['doc_type_name'])->first();
            if (null == $doc_exist) {
                DocumentType::create([
                    'doc_type_name' => $data['doc_type_name'],
                    'status' => $data['status'],
                ]);
                Session::flash('success', trans('language.Document type is created successfully'));
            } else {
                Session::flash('error', trans('language.Document type with this name already exist'));
             }
        // } catch (\Exception $e) {
        //     Log::error($e->getMessage());
        //     Session::flash('error', trans('language.Failed to create ddocument type.'));
        // }
    }
}