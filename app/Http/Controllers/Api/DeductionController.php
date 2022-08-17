<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Deduction;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class DeductionController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a Specific listing of the resource.
     * @return mixed
     */
    public function index()
    {
        $deduction = Deduction::all();

        if (!$deduction->isEmpty()) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Deductions has been Recieved.";
            $this->responseData['data'] = $deduction;
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
            'template_id' => 'required',
            'name' => 'required',
            'amount' => 'required',
            'type' => 'required',
        ]);
        $deduction = new Deduction();
        $deduction->template_id = $request->template_id;
        $deduction->deduction_name = $request->name;
        $deduction->amount = $request->amount;
        $deduction->type = $request->type;
        $deduction->save();
        if ($deduction) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Deduction has been Added.";
            $this->responseData['data'] = $deduction;
            $this->status = 200;
        }
        return $this->apiResponse();
    }

    /**
     * Display the specified resource.
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $deduction = Deduction::find($id);

        if ($deduction) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Deduction has been Recieved.";
            $this->responseData['data'] = $deduction;
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
            'template_id' => 'required',
            'name' => 'required',
            'amount' => 'required',
            'type' => 'required',
        ]);
        $deduction = Deduction::find($id);
        $deduction->template_id = $request->template_id;
        $deduction->deduction_name = $request->name;
        $deduction->amount = $request->amount;
        $deduction->type = $request->type;
        $deduction->save();
        if ($deduction) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Deduction has been Updated.";
            $this->responseData['data'] = $deduction;
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
        $deduction = Deduction::find($id);
        $deduction->delete();

        if ($deduction) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Deduction has been Deleted.";
            $this->responseData['data'] = $deduction;
            $this->status = 200;
        }
        return $this->apiResponse();
    }
}
