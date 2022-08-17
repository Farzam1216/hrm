<?php

namespace App\Http\Controllers;

use App;
use App\Domain\Approval\Actions\DeleteCustomApproval;
use App\Domain\Approval\Actions\DisableApproval;
use App\Domain\Approval\Actions\EditApproval;
use App\Domain\Approval\Actions\EnableApproval;
use App\Domain\Approval\Actions\RestoreStandardApproval;
use App\Domain\Approval\Actions\StoreApproval;
use App\Domain\Approval\Actions\UpdateApproval;
use App\Domain\Approval\Actions\ViewApprovals;
use Illuminate\Http\Request;
use Session;

class ApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->session()->forget('unauthorized_user');
        $locale=$request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $data = (new ViewApprovals())->execute();
        return view('admin.approvals.index')->with('locale', $locale)
            ->with('allEmployees', $data['allEmployees'])
            ->with('allCustomLevels', $data['allCustomLevels'])
            ->with('approvals', $data['approvalsAndPathTypes']['approvals'])
            ->with('locations', $data['approvalsAndPathTypes']['locations'])
            ->with('departments', $data['approvalsAndPathTypes']['departments'])
            ->with('statuses', $data['statuses']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($lang, Request $request)
    {
        $request->session()->forget('unauthorized_user');
        $locale=$request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        return view('admin.approvals.create')->with('locale', $lang);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $locale=$request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new StoreApproval())->execute($request);
        Session::flash('success', trans('language.Approval Added'));
        return redirect($locale . '/approvals')->with('locale', $locale);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($lang, Request $request, $id)
    {
        $locale=$request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $data = (new EditApproval())->execute($id);
        return view('admin.approvals.edit')->with('locale', $lang)->with('approval', $data['approval'])
                ->with('defaultFields', $data['defaultFields']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update($lang, Request $request, $id)
    {
        (new UpdateApproval())->execute($lang, $request, $id);
        Session::flash('success', trans('language.Form has been Updated Successfully'));
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($lang, $id)
    {
        //this method is only for custom approval
        (new DeleteCustomApproval())->execute($id);
        Session::flash('success', trans('language.Approval has been deleted successfully'));
        return redirect($lang . '/approvals')->with('locale', $lang);
    }

    public function disable(Request $request)
    {
        (new DisableApproval())->execute($request);
        Session::flash('success', trans('language.You have successfully disabled approval.'));
        return redirect()->back();
    }

    public function enable(Request $request)
    {
        (new EnableApproval())->execute($request);
        Session::flash('success', trans('language.You have successfully enabled approval.'));
        return redirect()->back();
    }

    public function restoreDefaultStandardApproval(Request $request)
    {
        (new RestoreStandardApproval())->execute($request);
        Session::flash('success', trans('language.You have successfully restored approval.'));
        return redirect()->back();
    }
}
