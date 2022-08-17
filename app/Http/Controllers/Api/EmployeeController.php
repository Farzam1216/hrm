<?php

namespace App\Http\Controllers\Api;

use DB;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use App\Domain\ACL\Models\Role;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\validateEmployee;
use App\Domain\Employee\Models\Employee;
use App\Domain\Employee\Actions\GetAllEmployees;
use App\Domain\Employee\Actions\StoreEmployeeAndAssignAdditionalDetails;

class EmployeeController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     * @return mixed
     */
    public function index()
    {
        $employee = (new GetAllEmployees)->execute();
        if (!$employee->isEmpty()) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Employees has been Recieved.";
            $this->responseData['data'] = $employee;
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
        $employee = Employee::find($id);
        if ($employee) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Employee has been Recieved.";
            $this->responseData['data'] = $employee;
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
    public function store(validateEmployee $request)
    {
        $locale = 'en';
        //TODO:: also perform js validation
        $storeEmployee = (new StoreEmployeeAndAssignAdditionalDetails())->execute($request, $locale);
        if ($storeEmployee) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Employee has been Added.";
            $this->responseData['data'] = $storeEmployee;
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
        //        $this->validate($request, [
        //            'firstname' => 'required',
        //            'lastname' => 'required',
        //            'official_email' => 'required|email|unique:employees,official_email,' . $id,
        //            'personal_email' => 'required|email|unique:employees,personal_email,' . $id,
        //            'contact_no' => 'required|size:11|unique:employees,contact_no,' . $id,
        //            'picture' => 'image|mimes:jpg,png,jpeg,gif,svg|max:1000',
        //        ]);
        $password = bcrypt("123456");
        $picture = "";
        if ($request->picture != "") {
            $picture = time() . '_' . $request->picture->getClientOriginalName();
            $request->picture->move('storage/employees/profile/', $picture);
            //$arr['picture'] = 'storage/employees/profile/' . $picture;
        }
        $employee = Employee::find($id);
        $employee->firstname = $request->firstname;
        $employee->lastname = $request->lastname;
        $employee->contact_no = $request->contact_no;
        $employee->emergency_contact = $request->emergency_contact;
        $employee->emergency_contact_relationship = $request->emergency_contact_relationship;
        $employee->password = $password;
        $employee->official_email = $request->official_email;
        $employee->personal_email = $request->personal_email;
        $employee->status = 1;
        $employee->employment_status_id = $request->employment_status;
        $employee->department_id = $request->department_id;
        $employee->designation = strtolower($request->designation);
        $employee->type = $request->type;
        $employee->nin = $request->nin;
        $employee->date_of_birth = $request->date_of_birth;
        $employee->current_address = $request->current_address;
        $employee->permanent_address = $request->permanent_address;
        $employee->city = $request->city;
        $employee->joining_date = $request->joining_date;
        $employee->gender = $request->gender;
        $employee->picture = 'storage/employees/profile/' . $picture;
        $employee->save();
        if ($employee) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Employees has been Updated.";
            $this->responseData['data'] = $employee;
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
        $employee = Employee::find($id)->delete();
        if ($employee) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Employees has been Deleted.";
            $this->responseData['data'] = $employee;
            $this->status = 200;
        }
        return $this->apiResponse();
    }
}
