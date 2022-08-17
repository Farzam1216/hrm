<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payroll\Allowance;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class AllowanceController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     * @return mixed
     */
    public function index()
    {
        $allowance = Allowance::all();
        if (!$allowance->isEmpty()) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Allowance has been Received.";
            $this->responseData['data'] = $allowance;
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
        $allowance = new Allowance();
        $allowance->template_id = $request->template_id;
        $allowance->allowance_name = $request->name;
        $allowance->amount = $request->amount;
        $allowance->type = $request->type;
        $allowance->save();
        if ($allowance) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Allowance has been Added.";
            $this->responseData['data'] = $allowance;
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
        $allowance = Allowance::find($id);
        if (!$allowance->isEmpty()) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Allowance has been Received.";
            $this->responseData['data'] = $allowance;
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
        $allowance = Allowance::find($id);
        $allowance->template_id = $request->template_id;
        $allowance->allowance_name = $request->name;
        $allowance->amount = $request->amount;
        $allowance->type = $request->type;
        $allowance->save();
        if ($allowance) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Allowance has been Updated.";
            $this->responseData['data'] = $allowance;
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
        $allowance = Allowance::find($id);
        $allowance->delete();

        if ($allowance) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Allowance has been Updated.";
            $this->responseData['data'] = $allowance;
            $this->status = 200;
        }
        return $this->apiResponse();
    }
}
