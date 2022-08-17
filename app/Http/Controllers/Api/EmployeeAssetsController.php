<?php

namespace App\Http\Controllers\Api;

use App\Domain\Employee\Models\Asset;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeAssetsController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     * @return mixed
     */
    public function index()
    {
        $assets = Asset::all();
        if (!$assets->isEmpty()) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Employee Assets has been Received.";
            $this->responseData['data'] = $assets;
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
        $assets = Asset::where('employee_id', $id)->get();
        if (!$assets->isEmpty()) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Employee Asset has been Received.";
            $this->responseData['data'] = $assets;
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
            'asset_category' => 'required',
            'asset_description' => 'required',
            'serial' => 'required',
            'assign_date' => 'required',
            'employee_id' => 'required',
        ]);
        $assets = new Asset();
        $assets->asset_category = $request->asset_category;
        $assets->asset_description = $request->asset_description;
        $assets->serial = $request->serial;
        $assets->assign_date = $request->assign_date;
        $assets->return_date = $request->return_date;
        $assets->employee_id = $request->employee_id;
        $assets->save();
        if ($assets) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Employee Assets has been Added.";
            $this->responseData['data'] = $assets;
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
            'asset_category' => 'required',
            'asset_description' => 'required',
            'serial' => 'required',
            'assign_date' => 'required',
            'employee_id' => 'required',
        ]);
        $employee_id = $request->employee_id;
        $assets = Asset::find($id);
        $assets->asset_category = $request->asset_category;
        $assets->asset_description = $request->asset_description;
        $assets->serial = $request->serial;
        $assets->assign_date = $request->assign_date;
        $assets->return_date = $request->return_date;
        $assets->employee_id = $employee_id;
        $assets->save();
        if ($assets) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Employee Assets has been Updated.";
            $this->responseData['data'] = $assets;
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
        $assets = DB::table('assets')->where('id', $id)->delete();
        if ($assets) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Employee Assets has been Deleted.";
            $this->responseData['data'] = $assets;
            $this->status = 200;
        }
        return $this->apiResponse();
    }
}
