<?php

namespace App\Http\Controllers;

use App;
use App\Domain\Employee\Models\Department;
use App\Domain\Employee\Models\Employee;
use App\Domain\Employee\Models\Team;
use App\Http\Requests\storeTeam;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Session;

class TeamController extends Controller
{
    /**
     * Show Teams Form The Storage.
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function index(Request $request)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $departments = Department::where('status', 'Active')->get();
        $teams = Team::with('department')->get();
        $employees = Employee::where('status', '!=', '0')->get();

        return view('admin.teams.index')->with('departments', $departments)->with('teams', $teams)->with('employees', $employees)->with('locale', $locale);
    }

    /**
     * Create New Teams.
     *
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @throws ValidationException
     */
    public function create(storeTeam $request)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $team_exist = Team::where('name', $request->team_name)->first();
        if ($team_exist == null) {
            $team = Team::create([
                'name' => $request->team_name,
                'department_id' => $request->department_id,
                'status' => $request->status,
            ]);
            Session::flash('success', trans('language.Team is created successfully'));
        } else {
            Session::flash('error', trans('language.Team with this name already exist'));
        }

        return Redirect::to(url($locale.'/teams'))->with('locale', $locale);
    }

    /**
     * Update Team Details.
     *
     * @param Request $request
     * @param $id
     *
     * @return RedirectResponse
     */
    public function update(Request $request, $lang, $id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $team = Team::find($id);
        $team->department_id = $request->dept_id;
        $team->name = $request->name;
        $team->status = $request->status;
        $team->save();
        Session::flash('success', trans('language.Team is updated successfully'));

        return Redirect::to(url($locale.'/teams'))->with('locale', $locale);
    }

    /**
     * Delete Team From The Storage.
     *
     * @param Request $request
     * @param $lang
     * @param $id
     *
     * @return RedirectResponse
     */
    public function delete(Request $request, $lang, $id)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $team = Team::find($id);
        $team->delete();
        Session::flash('success', trans('language.Team deleted successfully.'));

        return Redirect::to(url($locale.'/teams'))->with('locale', $locale);
    }
}
