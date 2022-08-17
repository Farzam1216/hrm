<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LeaveType;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     * @return mixed
     */
    public function index()
    {
        $leaveTypes = LeaveType::all();
        if (!$leaveTypes->isEmpty()) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Leave Type has been received.";
            $this->responseData['data'] = $leaveTypes;
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
            'name' => 'required',
            'amount' => 'required',
            'status' => 'required',
        ]);
        $leaveTypes = LeaveType::create([
            'name' => $request->name,
            'count' => $request->amount,
            'status' => $request->status,
        ]);

        if ($leaveTypes) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Leave Type has been Added.";
            $this->responseData['data'] = $leaveTypes;
            $this->status = 200;
        }
        return $this->apiResponse();
    }

    /**
     * Display a Specific listing of the resource.
     *
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        $leaveType = LeaveType::find($id);
        if ($leaveType) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Leave Type has been Recieved.";
            $this->responseData['data'] = $leaveType;
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
            'name' => 'required',
            'amount' => 'required',
            'status' => 'required',
        ]);
        $leaveType = LeaveType::find($id);
        $leaveType->name = $request->name;
        $leaveType->count = $request->amount;
        $leaveType->status = $request->status;
        $leaveType->save();
        if ($leaveType) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Leave Type has been Updated.";
            $this->responseData['data'] = $leaveType;
            $this->status = 200;
        }
        return $this->apiResponse();
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $leaveType = LeaveType::find($id);
        $leaveType->delete();
        if ($leaveType) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Leave Type has been Deleted.";
            $this->responseData['data'] = $leaveType;
            $this->status = 200;
        }
        return $this->apiResponse();
    }
}
