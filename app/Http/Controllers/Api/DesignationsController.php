<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Domain\Employee\Models\Designation;
use App\Domain\Employee\Actions\GetAllDesignations;

class DesignationsController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     * @return mixed
     */
    public function index()
    {
        $designations = (new GetAllDesignations)->execute();
        if (!$designations->isEmpty()) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Designations has been Recieved.";
            $this->responseData['data'] = $designations;
            $this->status = 200;
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
            'designation_name' => 'required',
            'status' => 'required',
        ]);
        $designations = [
            'designation_name' => $request->designation_name,
            'status' => $request->status,
        ];
        Designation::create($designations);
        if ($designations) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Designations has been Added.";
            $this->responseData['data'] = $designations;
            $this->status = 200;
        }
        return $this->apiResponse();
    }

    /**
     * Display a Specific listing of the resource.
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        $designations = Designation::find($id);
        if ($designations) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Designation has been Received.";
            $this->responseData['data'] = $designations;
            $this->status = 200;
        }
        return $this->apiResponse();
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param $id
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'designation_name' => 'required',
            'status' => 'required',
        ]);
        $designations = Designation::find($id);
        $designations->designation_name = $request->designation_name;
        $designations->status = $request->status;
        $designations->save();
        if ($designations) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Designations has been Updated.";
            $this->responseData['data'] = $designations;
            $this->status = 200;
        }
        return $this->apiResponse();
    }

    /**
     * Remove the specified resource from storage.
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        $designations = DB::table('designations')->where('id', $id)->delete();
        if ($designations) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Designations has been Deleted.";
            $this->responseData['data'] = $designations;
            $this->status = 200;
        }
        return $this->apiResponse();
    }
}
