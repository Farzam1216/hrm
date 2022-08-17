<?php

namespace App\Http\Controllers;

use App\Domain\Approval\Actions\ChangeAdvanceApproval;
use App\Domain\Approval\Actions\GetApprovalsAndPathTypes;
use App\Domain\Approval\Actions\RemoveAdvanceApproval;
use App\Domain\Approval\Actions\UpdateApprovalWorkflow;
use App\Domain\Approval\Actions\ViewAdvanceApprovals;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Session;

class AdvanceApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $data = (new ViewAdvanceApprovals())->execute();
        return view('admin.advance_approval.index')->with('locale', $locale)
            ->with('allEmployees', $data['allEmployees'])
            ->with('allCustomLevels', $data['allCustomLevels'])
            ->with('approvals', $data['approvalsAndPathTypes']['approvals'])
            ->with('departments', $data['approvalsAndPathTypes']['departments'])
            // ->with('divisions', $data['approvalsAndPathTypes']['divisions']) TODO: need it after changing employee model
            ->with('locations', $data['approvalsAndPathTypes']['locations']);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($lang, Request $request, $id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $data = (new ViewAdvanceApprovals())->execute();
        return view('admin.advance_approval.create')->with('locale', $locale)->with('id', $id)
            ->with('allEmployees', $data['allEmployees'])
            ->with('allCustomLevels', $data['allCustomLevels'])
            ->with('approvals', $data['approvalsAndPathTypes']['approvals'])
            ->with('departments', $data['approvalsAndPathTypes']['departments'])
           // ->with('divisions', $data['approvalsAndPathTypes']['divisions']) TODO: need it after changing employee model
            ->with('locations', $data['approvalsAndPathTypes']['locations']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $locale=$request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new UpdateApprovalWorkflow())->execute($request->all());
        Session::flash('success', trans('language.Successfully Added new Approval Path.'));
        return redirect(url($locale.'/advance-approvals'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($lang, Request $request, $id)
    {
        $locale=$request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $result = (new GetApprovalsAndPathTypes())->execute();
        return view('admin.advance_approval.edit')->with('locale', $locale)->with('approvals', $result['approvals'])->with('id', $id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($lang, Request $request, $id)
    {
        $locale=$request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new ChangeAdvanceApproval())->execute($request->all(), $id);

        return redirect(url($locale.'/advance-approvals'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($lang, $id)
    {
        (new RemoveAdvanceApproval())->execute($id);
        Session::flash('success', trans('language.Advance Approval has been deleted successfully'));
        return redirect(url($lang.'/approvals'))->with('locale', $lang);
    }
}
