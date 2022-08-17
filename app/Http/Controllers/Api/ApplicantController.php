<?php

namespace App\Http\Controllers\Api;

use App\Domain\Hiring\Models\Candidate;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class ApplicantController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $applicants = Candidate::all();
        if (!$applicants->isEmpty()) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Applicants has been Recieved.";
            $this->responseData['data'] = $applicants;
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
            'position' => 'required',
            'name' => 'required',
            'fname' => 'required',
            'avatar' => 'required|image',
            'city' => 'required',
            'cv' => 'required|mimes:doc,docx,pdf,txt',
            'job_status' => 'required',
        ]);
        $avatar = $request->avatar;
        $avatar_new_name = time() . $avatar->getClientOriginalName();
        $avatar->move('storage/uploads/applicants/image', $avatar_new_name);

        $cv = $request->cv;
        $cv_new_name = time() . $cv->getClientOriginalName();
        $cv->move('storage/uploads/applicants/cv', $cv_new_name);

        $applicant = Candidate::create([
            'name' => $request->name,
            'fname' => $request->fname,
            'email' => $request->email,
            'avatar' => 'storage/uploads/applicants/image/' . $avatar_new_name,
            'cv' => 'storage/uploads/applicants/cv/' . $cv_new_name,
            'city' => $request->city,
            'job_status' => $request->job_status,
            'job_id' => $request->position,
            'recruited' => 0,
        ]);
        if ($applicant) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Candidates has been Recieved.";
            $this->responseData['data'] = $applicant;
            $this->status = 200;
        }
        return $this->apiResponse();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $applicant = Candidate::find($id);
        if ($applicant) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Candidates has been Recieved.";
            $this->responseData['data'] = $applicant;
            $this->status = 200;
        }
        return $this->apiResponse();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $applicant = Candidate::find($id);
        $applicant->delete();
        if ($applicant) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Candidates has been Recieved.";
            $this->responseData['data'] = $applicant;
            $this->status = 200;
        }
        return $this->apiResponse();
    }
}
