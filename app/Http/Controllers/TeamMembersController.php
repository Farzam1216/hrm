<?php

namespace App\Http\Controllers;

use App;
use App\Domain\Employee\Models\Employee;
use App\Domain\Employee\Models\Team;
use App\Domain\Employee\Models\TeamMember;
use App\Http\Requests\storeTeamMember;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Session;

class TeamMembersController extends Controller
{
    /**
     * Show Teams Members.
     *
     * @return RedirectResponse
     */
    public function index()
    {
        session()->forget('unauthorized_user');
        $teams = Team::with('department')->get();
        $employees = Employee::where('status', '!=', '0')->get();

        return view('admin.teams.team_member')->with('teams', $teams)->with('employees', $employees);
    }

    /**
     * Add Employee To Teams.
     *
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @throws ValidationException
     */
    public function create(storeTeamMember $request)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $member_exist = TeamMember::where('employee_id', $request->team_member)->where('team_id', $request->team_id)->first();
        if ($member_exist == null) {
            TeamMember::create([
                'employee_id' => $request->team_member,
                'team_id' => $request->team_id,
            ]);
            Session::flash('success', trans('language.Member added to team successfully'));
        } else {
            Session::flash('error', trans('language.This employee already exist in this team'));
        }

        return Redirect::to(url($locale.'/teams'))->with('locale', $locale);
    }

    /**
     * Edit Teams Members.
     *
     * @param $lang
     * @param $id
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function edit($lang, $id, Request $request)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $team_name = Team::find($id);
        $team_members = TeamMember::with('employee')->where('team_id', $id)->get();

        return view('admin.teams.team_member_edit')->with('team_members', $team_members)->with('team_name', $team_name)->with('locale', $locale);
    }

    /**
     * Delete Employee From Team.
     *
     * @param $lang
     * @param $id
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function delete($lang, $id, Request $request)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $member_name = TeamMember::where('id', $id);
        $member_name->delete();
        Session::flash('success', trans('language.Employee deleted from team successfully'));

        return redirect()->back()->with('locale', $locale);
    }
}
