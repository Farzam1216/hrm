<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class JobController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jobs = Job::with('department', 'designation', 'branch')->get();
        $data = [];
        $i = 0;
        foreach ($jobs as $job) {
            $data[$i]['Jobtitle'] = $job->title;
            $data[$i]['Description'] = $job->description;
            $data[$i]['DesignationName'] = $job->designation->designation_name;
            $data[$i]['DepartmentName'] = $job->department->department_name;
            $data[$i]['BranchName'] = $job->branch->name;
            $i++;
        }
        if ($data) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Jobs has been Recieved.";
            $this->responseData['data'] = $data;
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
            'title' => 'required',
            'designation_id' => 'required',
            'location_id' => 'required',
            'department_id' => 'required',
            'description' => 'required',
        ]);
        $job = Job::create([
            'title' => $request->title,
            'location_id' => $request->location_id,
            'department_id' => $request->department_id,
            'designation_id' => $request->designation_id,
            'description' => $request->description,
        ]);
        if ($job) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Jobs has been Added.";
            $this->responseData['data'] = $job;
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
        $job = Job::find($id);
        if ($job) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Jobs has been Updated.";
            $this->responseData['data'] = $job;
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
            'title' => 'required',
            'designation_id' => 'required',
            'location_id' => 'required',
            'department_id' => 'required',
            'description' => 'required',
        ]);
        $job = Job::find($id);
        $job->title = $request->title;
        $job->location_id = $request->location_id;
        $job->department_id = $request->department_id;
        $job->designation_id = $request->designation_id;
        $job->description = $request->description;
        $job->save();

        if ($job) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Jobs has been Updated.";
            $this->responseData['data'] = $job;
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
        $job = Job::where('id', $id)->first();
        $job->delete();

        if ($job) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Jobs has been Deletd.";
            $this->responseData['data'] = $job;
            $this->status = 200;
        }
        return $this->apiResponse();
    }
}
