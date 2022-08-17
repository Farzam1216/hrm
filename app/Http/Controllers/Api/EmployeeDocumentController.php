<?php

namespace App\Http\Controllers\Api;

use App\Domain\Employee\Models\EmployeeDocument;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use DB;
use Illuminate\Http\Request;

class EmployeeDocumentController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a Specific listing of the resource.
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        $employeeDocument = EmployeeDocument::where('employee_id', $id)->get();
        if (!$employeeDocument->isEmpty()) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Employee Document has been Received.";
            $this->responseData['data'] = $employeeDocument;
        }
        return $this->apiResponse();
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'file' => 'required',
            'status' => 'required',
            'type' => 'required',
        ]);
        $documentExist = EmployeeDocument::where('doc_name', $request->name)->first();
        if ($documentExist == null) {
            $documentFile = time() . '_' . trim($request->files->get('doc_file')->getClientOriginalName());
            $documentFile = preg_replace('/\s+/', '', $documentFile);
            $request->files->get('doc_file')->move('storage/documents/', $documentFile);
            $employeeDocument = EmployeeDocument::create([
                'employee_id' => $request->employee_id,
                'doc_name' => $request->name,
                'doc_type' => $request->type,
                'doc_file' => 'storage/documents/' . $documentFile,
                'status' => $request->status,
            ]);
            $data = $employeeDocument->toArray();
            if ($data) {
                $this->responseData['response'] = 1;
                $this->responseData['message'] = "Employee Document has been Added.";
                $this->responseData['data'] = $data;
            }
            return $this->apiResponse();
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        $employeeDocument = DB::table('employee_documents')->where('id', $id)->delete();
        if ($employeeDocument) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Employee Document has been Deleted.";
            $this->responseData['data'] = $employeeDocument;
        }
        return $this->apiResponse();
    }

    /**
     * Update the specified resource in storage.
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function update($id, Request $request)
    {
        $documentUpdate = EmployeeDocument::find($id);
        $documentUpdate->doc_name = $request->name;
        $documentUpdate->doc_type = $request->type;
        $documentUpdate->status = $request->status;
        //$document_file = time() . '_' . trim($request->files->get('doc_file')->getClientOriginalName());
        //$document_file= preg_replace('/\s+/', '', $document_file);
        // $request->files->get('doc_file')->move('storage/documents/', $document_file);
        //$doc_update->doc_file = 'storage/documents/' . $document_file;
        $documentUpdate->save();
        $data = $documentUpdate->toArray();
        if ($data) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Employee Document has been Updated.";
            $this->responseData['data'] = $data;
        }
        return $this->apiResponse();
    }
}
