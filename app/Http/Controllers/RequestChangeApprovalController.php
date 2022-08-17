<?php

namespace App\Http\Controllers;

use App\Domain\Approval\Actions\EditRequestChangeApproval;
use App\Domain\Approval\Actions\StoreChangeRequest;
use Illuminate\Http\Request;

class RequestChangeApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($lang, $id, $approvalId)
    {
    }

    /**
     * Show the form for creating a new resources.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store($lang, $id, Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $employeeId
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($lang, $employeeId, $approvalId)
    {
        $data= (new EditRequestChangeApproval())->execute($employeeId, $approvalId);
        return view('admin.approvals.requestchange', ['title' => 'Request a change'])
            ->with('employeeId', $employeeId)
            ->with('approvalId', $approvalId)
            ->with('requestedFieldID', $data['requestedFields']->id)
            ->with('requestApprovals', $data['requestApprovals'])
            ->with('requestedFields', $data['requestChangeApprovals'])
            ->with('locale', $lang);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $lang
     * @param $employeeID
     * @param $approvalID
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update($lang, $employeeID, $approvalID, Request $request)
    {
        $storeChangeRequest = new StoreChangeRequest();
        $approvalRequest = $storeChangeRequest->execute($employeeID, $approvalID, $request);
        if ($approvalRequest) {
            return redirect($lang . '/employee/edit/' . $employeeID)->with('success', 'Change request saved successfully');
        } else {
            return redirect($lang . '/employee/edit/' . $employeeID)->with('error', 'Change request failed.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
}
