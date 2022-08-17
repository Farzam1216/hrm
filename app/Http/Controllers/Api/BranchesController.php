<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Domain\Employee\Models\Branch;
use App\Domain\Employee\Models\Location;
use App\Domain\Employee\Actions\GetAllLocations;

class BranchesController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     * @return mixed
     */
    public function index()
    {
        $locations = (new GetAllLocations)->execute();
        if (!$locations->isEmpty()) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Branches has been Received.";
            $this->responseData['data'] = $locations;
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
            'status' => 'required',
            'timing_start' => 'required',
            'timing_off' => 'required|after:timing_start',
            'address' => 'required',
            'phone_number' => 'required',
        ]);
        $branches = [
            'name' => $request->name,
            'status' => $request->status,
            'timing_start' => Carbon::parse($request->timing_start),
            'timing_off' => Carbon::parse($request->timing_off),
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'weekend' => $request->weekend,
        ];
        Branch::create($branches);
        if ($branches) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Branch has been Added.";
            $this->responseData['data'] = $branches;
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
        $branch = Branch::find($id);
        if (!empty($branch)) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Branch has been Received.";
            $this->responseData['data'] = $branch;
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
            'status' => 'required',
            'timing_start' => 'required',
            'timing_off' => 'required|after:timing_start',
            'address' => 'required',
            'phone_number' => 'required',
        ]);
        $branch = Branch::find($id);
        $branch->name = $request->name;
        $branch->status = $request->status;
        $branch->timing_start = Carbon::parse($request->timing_start);
        $branch->timing_off = Carbon::parse($request->timing_off);
        $branch->address = $request->address;
        $branch->phone_number = $request->phone_number;
        $branch->weekend = json_encode($request->weekend);
        $branch->save();
        if ($branch) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Branch has been Updated.";
            $this->responseData['data'] = $branch;
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
        $branch = DB::table('branches')->where('id', $id)->delete();
        if ($branch) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Branch has been Deletd.";
            $this->responseData['data'] = $branch;
            $this->status = 200;
        }
        return $this->apiResponse();
    }
}
