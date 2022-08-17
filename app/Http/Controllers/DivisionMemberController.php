<?php

namespace App\Http\Controllers;

use App;
use App\Domain\Employee\Models\Division;
use App\Domain\Employee\Models\DivisionMembers;
use App\Domain\Employee\Models\Employee;
use App\Http\Requests\storeDivisionMember;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Session;

class DivisionMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        session()->forget('unauthorized_user');
        $division = Division::with('department')->get();
        $employees = Employee::where('status', '!=', '0')->get();

        return view('admin.division.division_member_edit')->with('divisions', $division)->with('employees', $employees)->with('locale', $locale);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @throws ValidationException
     */
    public function create(storeDivisionMember $request)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $member_exist = DivisionMembers::where('employee_id', $request->division_member)->where('division_id', $request->division_id)->first();
        if ($member_exist == null) {
            DivisionMembers::create([
                'employee_id' => $request->division_member,
                'division_id' => $request->division_id,
            ]);
            Session::flash('success', trans('language.Member added to team successfully'));
        } else {
            Session::flash('error', trans('language.This employee already exist in this team'));
        }

        return Redirect::to(url($locale.'/division'))->with('locale', $locale);
    }

    /**
     * Display the specified resource.
     *
     * @param $lang
     * @param int     $id
     * @param Request $request
     *
     * @return Response
     */
    public function edit($lang, $id, Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $division_name = Division::find($id);
        $division_members = DivisionMembers::with('employee')->where('division_id', $id)->get();

        return view('admin.division.division_member_edit')->with('division_members', $division_members)->with('division_name', $division_name)->with('locale', $locale);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $lang
     * @param int     $id
     * @param Request $request
     *
     * @return Response
     */
    public function delete($lang, $id, Request $request)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $member_name = DivisionMembers::where('id', $id);
        $member_name->delete();
        Session::flash('success', trans('language.Employee deleted from team successfully'));

        return redirect()->back()->with('locale', $locale);
    }
}
