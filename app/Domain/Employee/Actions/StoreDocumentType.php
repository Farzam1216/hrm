<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\DocumentType;
use Illuminate\Support\Facades\Log;
use Session;

class StoreDocumentType
{
    /**
     * @param $data
     * @return void
     *
     */
    public function execute($data)
    {
        try {
            $doc_exist = DocumentType::where('doc_type_name', $data['doc_type_name'])->first();
            if (null == $doc_exist) {
                return DocumentType::create([
                    'doc_type_name' => $data['doc_type_name'],
                    'status' => $data['status'],
                ]);
                
            } else {
                return false;
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Session::flash('error', trans('language.Failed to create document type.'));
        }
    }
}
