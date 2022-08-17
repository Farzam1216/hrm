<?php

namespace App\Http\Controllers\Api;

use App\App\Domain\Employee\Models\Note;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeNotesController extends Controller
{
    use App\Traits\ApiResponseTrait;

    /**
     * Display a Specific listing of the resource.
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        $notes = Note::where('employee_id', $id)->get();
        if (!$notes->isEmpty()) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Employee Notes has been Received.";
            $this->responseData['data'] = $notes;
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
            'note' => 'required',
            'employee_id' => 'required',
        ]);
        $userName = Auth::user()->firstname;
        $note = new Note();
        $note->username = $userName;
        $note->note = $request->note;
        $note->employee_id = $request->employee_id;
        $note->save();
        $data = $note->toArray();
        if ($data) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Employee Notes has been Added.";
            $this->responseData['data'] = $data;
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
            'note' => 'required',
            'employee_id' => 'required',
        ]);
        $note = Note::find($id);
        if ($note->username == Auth::user()->firstname) {
            $note->username = Auth::user()->firstname;
            $note->note = $request->note;
            $note->employee_id = $request->employee_id;
            $note->save();
            $data = $note->toArray();
            if ($data) {
                $this->responseData['response'] = 1;
                $this->responseData['message'] = "Employee Notes has been Updated.";
                $this->responseData['data'] = $data;
                $this->status = 200;
            }
            return $this->apiResponse();
        } else {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "You are not Authorized.";
            $this->responseData['data'] = '';
            return $this->apiResponse();
            $this->status = 404;
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function destroy(Request $request, $id)
    {
        $note = Note::find($id);
        if ($note->username == Auth::user()->firstname) {
            $note->delete();
            if ($note) {
                $this->responseData['response'] = 1;
                $this->responseData['message'] = "Employee Notes has been Deleted.";
                $this->responseData['data'] = $note;
                $this->status = 200;
            }
            return $this->apiResponse();
        } else {
            if (!$note) {
                $this->responseData['response'] = 1;
                $this->responseData['message'] = "You are not Authorized.";
                $this->responseData['data'] = '';
                $this->status = 404;
            }
            return $this->apiResponse();
        }
    }
}
