<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SalaryTemplate;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class SalaryTemplateController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     * @return mixed
     */
    public function index()
    {
        $template = SalaryTemplate::all();
        if (!$template->isEmpty()) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Salary Template has been Recieved.";
            $this->responseData['data'] = $template;
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
            'salary' => 'required',
            'status' => 'required',
            'deduction' => 'required',
        ]);
        $template = new SalaryTemplate();
        $template->template_name = $request->name;
        $template->basic_salary = $request->salary;
        $template->status = $request->status;
        $template->deduction = $request->deduction;
        $template->save();
        if ($template) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Salary Template has been Added.";
            $this->responseData['data'] = $template;
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
        $template = SalaryTemplate::find($id);
        if ($template) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Salary Template has been Received.";
            $this->responseData['data'] = $template;
            $this->status = 200;
        }
        return $this->apiResponse();
    }

    /**
     * Update the specified resource in storage.
     * @param $id
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update($id, Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'salary' => 'required',
            'status' => 'required',
            'deduction' => 'required',
        ]);
        $template = SalaryTemplate::find($id);
        $template->template_name = $request->name;
        $template->basic_salary = $request->salary;
        $template->status = $request->status;
        $template->deduction = $request->deduction;
        $template->save();
        if ($template) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Salary Template has been Updated.";
            $this->responseData['data'] = $template;
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
        $template = SalaryTemplate::find($id);
        $template->delete();
        if ($template) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Salary Template has been Deletd.";
            $this->responseData['data'] = $template;
            $this->status = 200;
        }
        return $this->apiResponse();
    }
}
