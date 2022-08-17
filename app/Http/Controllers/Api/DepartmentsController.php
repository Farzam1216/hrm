<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Domain\Employee\Models\Department;
use App\Domain\Employee\Actions\GetAllDepartments;

class DepartmentsController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     * @return mixed
     */
    public function index()
    {
        $departments = (new GetAllDepartments)->execute();
        if (!$departments->isEmpty()) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Departments has been Received.";
            $this->responseData['data'] = $departments;
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
            'department_name' => 'required',
            'status' => 'required',
        ]);
        $department = [
            'department_name' => $request->department_name,
            'status' => $request->status,
        ];
        Department::create($department);
        if ($department) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Departments has been Added.";
            $this->responseData['data'] = $department;
            $this->status = 200;
        }
        return $this->apiResponse();
    }

    public function show($id)
    {
        $departments = Department::find($id);
        if ($departments) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Department has been Recieved.";
            $this->responseData['data'] = $departments;
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
            'department_name' => 'required',
            'status' => 'required',
        ]);
        $department = Department::find($id);
        $department->department_name = $request->department_name;
        $department->status = $request->status;
        $department->save();
        if ($department) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Departments has been Updated.";
            $this->responseData['data'] = $department;
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
        $department = DB::table('departments')->where('id', $id)->delete();
        if ($department) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Departments has been Deleted.";
            $this->responseData['data'] = $department;
            $this->status = 200;
        }
        return $this->apiResponse();
    }
}
