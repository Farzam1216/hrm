<?php

namespace App\Http\Controllers\Api;

use App\Domain\Hiring\Models\Candidate;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;

class JobHiringController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the Trashed Application.
     * @return mixed
     */
    public function trashed()
    {
        $candidates = Candidate::onlyTrashed()->get();
        if (!$candidates->isEmpty()) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Trashed Candidates has been Received.";
            $this->responseData['data'] = $candidates;
            $this->status = 200;
        }
        return $this->apiResponse();
    }

    /**
     * Remove the specified Application from storage.
     * @param $id
     * @return mixed
     */
    public function kill($id)
    {
        $candidate = Candidate::withTrashed()->where('id', $id)->first();
        $candidate->forceDelete();
        if ($candidate) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Killed Candidate has been Received.";
            $this->responseData['data'] = $candidate;
            $this->status = 200;
        }
        return $this->apiResponse();
    }

    /**
     * Restore the specified Application in storage.
     * @param $id
     * @return mixed
     */
    public function restore($id)
    {
        $candidate = Candidate::withTrashed()->where('id', $id)->first();
        $candidate->restore();
        if ($candidate) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Restored Candidate has been Received.";
            $this->responseData['data'] = $candidate;
            $this->status = 200;
        }
        return $this->apiResponse();
    }

    /**
     * Display a listing of the Applications
     * @return mixed
     */
    public function hiredApplicants()
    {
        $candidate = Candidate::where('recruited', 1)->get();
        if ($candidate) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Hired Candidates has been Received.";
            $this->responseData['data'] = $candidate;
            $this->status = 200;
        }
        return $this->apiResponse();
    }

    /**
     * Hire a Specified Application.
     * @param $id
     * @return mixed
     */
    public function hire($id)
    {
        $candidate = Candidate::find($id);
        $candidate->recruited = 1;
        $candidate->save();
        if ($candidate) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Hired Candidates has been Received.";
            $this->responseData['data'] = $candidate;
            $this->status = 200;
        }
        return $this->apiResponse();
    }

    /**
     * Retire a Specified Application.
     * @param $id
     * @return mixed
     */
    public function retire($id)
    {
        $candidate = Candidate::find($id);
        $candidate->recruited = 0;
        $candidate->save();
        if ($candidate) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Hired Candidates have been Received.";
            $this->responseData['data'] = $candidate;
            $this->status = 200;
        }
        return $this->apiResponse();
    }
}
