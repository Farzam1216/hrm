<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Domain\Employee\Actions\StoreDocumentType;
use App\Domain\Employee\Actions\DeleteDocumentType;
use App\Domain\Employee\Actions\UpdateDocumentType;
use App\Domain\Employee\Actions\GetAllDocumentTypes;
use App\Traits\ApiResponseTrait;

class DocumentTypeController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $doc_types = (new GetAllDocumentTypes())->execute();
        return response()->json([
            'documentType'=>$doc_types
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $doc_type = (new StoreDocumentType())->execute($request->all());
        if ($doc_type) {
            return response()->json([
                'message' => 'Document type is created successfully',
                'documentType'=>$doc_type
            ],200);
        } else {
            return response()->json([
                'message' => 'Document is with this name is already exist',
            ],200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $doc_type = (new UpdateDocumentType())->execute($id, $request);
        if ($doc_type) {
            return response()->json([
                'message' => 'Document type is updated successfully.',
                'documentType'=>$doc_type
            ],200);
        } else {
            return response()->json([
                'message' => 'Failed to update document type.',
            ],200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $response = (new DeleteDocumentType())->execute($id);
        if ($response) {
            return response()->json([
                'message' => 'Document type deleted successfully.',
            ],200);
        }
    }
}
