<?php

namespace App\Http\Controllers;

use App;
use App\Domain\Employee\Models\Designation;
use App\Domain\Employee\Models\Employee;
use App\Domain\Employee\Models\OrganizationHierarchy;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class OrganizationHierarchyController extends Controller
{
    public $hierarchy = '';
    public $designations = [

    ];

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function __constructor()
    {
        $designations = Designation::all();
        foreach ($designations as $designation) {
            $this->designations[$designation->designation_name] = $designation->designation_name;
        }
    }

    /**
     * Process Hierarchy
     * @return void
     */
    public function processHierarchy()
    {
        $hierarchy = $this->hierarchy();

        foreach ($hierarchy as $key => $row) {
            if (isset($row['children'])) {
                foreach ($row['children'] as $child) {
                    # code...
                }
                $this->hierarchy[$key]->children = $row;
            }
        }
    }

    /**
     * Show Employees Hierarchy
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $organization_hierarchies = OrganizationHierarchy::with('employee')
            ->with('lineManager')
            ->with('parentEmployee')
            ->with('childs')
            ->get();
        $hierarchy = '';
        if ($organization_hierarchies->count() > 0) {
            $this->hierarchy .= '[';
            $this->myhire($organization_hierarchies);
            $this->hierarchy .= ']';
            $this->hierarchy = str_replace('},]', '}]', $this->hierarchy);
            $hierarchy = json_decode($this->hierarchy);
            $hierarchy = json_encode($hierarchy[0]);
        }
        return view('admin.organization_hierarchy.index')->with([
            'organization_hierarchies' => $organization_hierarchies,
            'hierarchy' => $hierarchy,
            'locale' => $locale,
        ]);
    }


    public function myhire($organization_hierarchies)
    {
        foreach ($organization_hierarchies as $organization_hierarchy) {
            $this->hierarchy .= '{
                "id": "' . $organization_hierarchy->id . '", 
                "employee_id": "' . $organization_hierarchy->employee->id . '", 
                "name": "' . $organization_hierarchy->employee->firstname . ' ' . $organization_hierarchy->employee->lastname . '", 
                "title": "' . $organization_hierarchy->employee->designation . '"';

            if (count($organization_hierarchy->childs) > 0) {
                $this->hierarchy .= ',"children": [';
                $this->myhire($organization_hierarchy->childs);
                $this->hierarchy .= ']';
            }
            $this->hierarchy .= '},';
        }
    }

    /**
     * Show the form for creating a Hierarchy.
     *
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        $request->session()->forget('unauthorized_user');
        $all_controllers = [];
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $employees = Employee::where('status', '!=', '0')->get();
        $OrganizationHierarchyCnt = OrganizationHierarchy::all()->count();
        return view('admin.organization_hierarchy.create')->with([
            'employees' => $employees,
            'OrganizationHierarchyCnt' => $OrganizationHierarchyCnt,
            'locale' => $locale,
        ]);
    }

    /**
     * Store a New Hierarchy Employee
     *
     * @param Request $request
     * @return Response
     */

    public function store(Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $true1 = OrganizationHierarchy::where('employee_id', $request->employee_id)->first();
        $true2 = OrganizationHierarchy::where('parent_id', $request->employee_id)->where(
            'employee_id',
            $request->parent_id
        )->first();
        $true3 = OrganizationHierarchy::where('employee_id', $request->employee_id)->first();
        if ($request->parent_id == $request->employee_id) {
            Session::flash('error', trans('language.Leave type deleted successfully'));
        } elseif (isset($true1) && $true1->parent_id != null && $request->parent_id != null) {
            Session::flash('error', trans('language.Employee could not have two parents'));
        } elseif (isset($true2)) {
            Session::flash('error', trans('language.Child Could not be parent of their parent'));
        } elseif ($true3) {
            if ($true3->parent_id == null) {
                Session::flash('error', trans('language.There Can not be parent of head of organization'));
            }
        } else {
            OrganizationHierarchy::create([
                'employee_id' => $request->employee_id,
                'parent_id' => $request->parent_id,
            ]);
            Session::flash('success', trans('language.Employee added to Organization Hierarchy succesfully'));
        }
        return Redirect::to(url($locale.'/organization-hierarchy'))->with('locale', $locale);
    }

    /**
     * Edit Hierarchy Detail
     *
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function edit($id, Request $request)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $employees = Employee::all();
        $organization_hierarchy = OrganizationHierarchy::find($id);
        return view('admin.organization_hierarchy.edit')->with([
            'organization_hierarchy' => $organization_hierarchy,
            'employees' => $employees,
            'locale' => $locale,
        ]);
    }

    /**
     * Update the Hierarchy Details
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $organization_hierarchy = OrganizationHierarchy::find($id);
        $organization_hierarchy->employee_id = $request->employee_id;
        $organization_hierarchy->line_manager_id = $request->line_manager_id;
        $organization_hierarchy->parent_id = $request->parent_id;
        $organization_hierarchy->save();
        return redirect($locale.'/organization-hierarchy')->with('success', trans('language.Employee updated in Organization Hierarchy succesfully'))->with('locale', $locale);
    }

    /**
     * Remove the specified resource from Hierarchy.
     *
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function destroy($id, Request $request)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        OrganizationHierarchy::where('parent_id', $id)->delete();
        OrganizationHierarchy::where('employee_id', $id)->delete();
        return redirect()->back()
            ->with('success', trans('language.Employee & his subordinates in OrganizationHierarchy are deleted succesfully'));
    }
}
