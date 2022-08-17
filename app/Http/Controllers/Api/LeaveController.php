<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaveController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $leaves = Leave::all();

        if (!$leaves->isEmpty()) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = 'Leaves has been received.';
            $this->responseData['data'] = $leaves;
            $this->status = 200;
        }
        return $this->apiResponse();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'employee_id' => 'required',
            'leave_type' => 'required',
            'datefrom' => 'required',
            'dateto' => 'required',
            'cc_to' => 'required|email',
            'subject' => 'required',
            'description' => 'required',
            'point_of_contact' => 'required',
            'status' => 'required',
        ]);
        $leave = [
            'employee_id' => $request->employee_id,
            'leave_type' => $request->leave_type,
            'datefrom' => $request->datefrom,
            'dateto' => $request->dateto,
            'cc_to' => $request->cc_to,
            'subject' => $request->subject,
            'description' => $request->description,
            'point_of_contact' => $request->point_of_contact,
            'line_manager' => $request->line_manager,
            'status' => $request->status,
        ];
        Leave::create($leave);

        if ($leave) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = 'Leave has been Added.';
            $this->responseData['data'] = $leave;
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
        $leave = Leave::where('id', $id)->get();
        if (!$leave->isEmpty()) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Leave has been Recieved.";
            $this->responseData['data'] = $leave;
            $this->status = 200;
        }
        return $this->apiResponse();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'employee_id' => 'required',
            'leave_type' => 'required',
            'datefrom' => 'required',
            'dateto' => 'required',
            'cc_to' => 'required|email',
            'subject' => 'required',
            'description' => 'required',
            'point_of_contact' => 'required',
            'status' => 'required',
        ]);
        $leave = [
            'employee_id' => $request->employee_id,
            'leave_type' => $request->leave_type,
            'datefrom' => $request->datefrom,
            'dateto' => $request->dateto,
            'cc_to' => $request->cc_to,
            'subject' => $request->subject,
            'description' => $request->description,
            'point_of_contact' => $request->point_of_contact,
            'line_manager' => $request->line_manager,
            'status' => $request->status,
        ];
        Leave::where('id', $id)->update($leave);
        if ($leave) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Leave has been Updated.";
            $this->responseData['data'] = $leave;
            $this->status = 200;
        }
        return $this->apiResponse();
    }

    /**
     * Remove the specified resource from storage.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $leave = DB::table('leaves')->where('id', $id)->delete();
        if ($leave) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Leave has been Deleted.";
            $this->responseData['data'] = $leave;
            $this->status = 200;
        }
        return $this->apiResponse();
    }
}
