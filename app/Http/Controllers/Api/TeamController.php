<?php

namespace App\Http\Controllers\Api;

use App\App\Domain\Employee\Models\Team;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     * @return mixed
     */
    public function index()
    {
        $teams = Team::all();
        if ($teams) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Teams has been Received.";
            $this->responseData['data'] = $teams;
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
            'team_name' => 'required',
            'department_id' => 'required',
            'status' => 'required',
        ]);
        $team = Team::create([
            'name' => $request->team_name,
            'department_id' => $request->department_id,
            'status' => $request->status,
        ]);
        if ($team) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Teams has been Received.";
            $this->responseData['data'] = $team;
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
        $team = Team::find($id);
        if ($team) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Teams has been Received.";
            $this->responseData['data'] = $team;
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
            'dept_id' => 'required',
            'name' => 'required',
            'status' => 'required',
        ]);
        $team = Team::find($id);
        $team->department_id = $request->dept_id;
        $team->name = $request->name;
        $team->status = $request->status;
        $team->save();
        if ($team) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Team has been Updated.";
            $this->responseData['data'] = $team;
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
        $team = Team::find($id);
        $team->delete();
        if ($team) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Team has been Deleted.";
            $this->responseData['data'] = $team;
            $this->status = 200;
        }
        return $this->apiResponse();
    }
}
