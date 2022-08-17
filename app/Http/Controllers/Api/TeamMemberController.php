<?php

namespace App\Http\Controllers\Api;

use App\App\Domain\Employee\Models\Department;
use App\App\Domain\Employee\Models\Employee;
use App\App\Domain\Employee\Models\Team;
use App\App\Domain\Employee\Models\TeamMember;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class TeamMemberController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     * @return mixed
     */
    public function index()
    {
        $teams = Team::all();
        $data = [];
        $i = 0;
        $j = 1;
        foreach ($teams as $team) {
            $employee_id = TeamMember::where('team_id', $team->id)->select('employee_id')->get();
            $data[$i]['teamname'] = $team->name;
            $deparmentId = $team->department_id;
            $data[$i]['Department_name'] = Department::find($deparmentId)->department_name;
            foreach ($employee_id as $id) {
                $data[$i]['Member_name ' . $j] = Employee::find($id->employee_id)->firstname;
                $j++;
            }
            $i++;
            $j = 1;
        }
        if ($data) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Team Members has been Recieved.";
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
            'team_id' => 'required',
            'employee_id' => 'required',
        ]);
        $teamMember = TeamMember::create([
            'employee_id' => $request->employee_id,
            'team_id' => $request->team_id,
        ]);
        if ($teamMember) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Team Member has been Added.";
            $this->responseData['data'] = $teamMember;
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
        $teamMember = TeamMember::find($id);
        if ($teamMember) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Team Member has been Recieved.";
            $this->responseData['data'] = $teamMember;
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
            'team_id' => 'required',
            'employee_id' => 'required',
        ]);
        $teamMember = TeamMember::find($id);
        $teamMember->employee_id = $request->employee_id;
        $teamMember->team_id = $request->team_id;
        $teamMember->save();
        if ($teamMember) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Team Member has been Updated.";
            $this->responseData['data'] = $teamMember;
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
        $memberName = TeamMember::where('id', $id)->first();
        $memberName->delete();
        if ($memberName) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Team Member has been Deletd.";
            $this->responseData['data'] = $memberName;
            $this->status = 200;
        }
        return $this->apiResponse();
    }
}
