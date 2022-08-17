<?php

namespace App\Http\Controllers;

use App;
use App\Domain\Employee\Actions\EditEmployee;
use App\Domain\Employee\Actions\GetAuthorizedUserPermissions;
use App\Domain\Employee\Actions\GetInformationRequiredToCreateAnEmployee;
use App\Domain\Employee\Actions\StoreEmployeeAndAssignAdditionalDetails;
use App\Domain\Employee\Actions\UpdateEmployee;
use App\Domain\Employee\Actions\ViewAllEmployees;
use App\Domain\Employee\Actions\UpdateEmployeeSalary;
use App\Http\Requests\validateEmployee;
use App\Http\Requests\storeEmployee;
use App\Http\Requests\updateSalary;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Session;
use App\Domain\Employee\Models\Employee;

class EmployeeController extends Controller
{
    /**
     * Show All Employees With The Filter By Status.
     *
     * @param $lang
     * @param Request $request
     * @param string $id
     *
     * @return RedirectResponse
     */
    public function index($lang, Request $request, $id = '') // lang param is removed
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        //FIXME: $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "People Management"], ['name' => "Employees"]
        ];
        $data = (new ViewAllEmployees())->execute($id);
        return view('admin.employees.index',
            ['title' => 'All Employees',
                'breadcrumbs' => $breadcrumbs,
                'employees' => $data['info']['employee'],
                'active_employees' => $data['info']['active_employees'],
                'filters' => $data['info']['filter'],
                'permissions' => $data['permissions'],
                'hrManagerPermission' => $data['hr-manager-permission'],
                'locale' => $locale,
                'basicPermissions' => $data['basicPermissions']
            ]);
    }

    /**
     * Show Form For Adding New Employee To The System.
     *
     * @param Request $request
     *
     * @return Factory|View
     */
    public function create(Request $request)
    {
        // $request->session()->forget('unauthorized_user');FIXME: commented for vue
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $data = (new GetInformationRequiredToCreateAnEmployee())->execute();
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "People Management"], ['link' => "{$locale}/employees", 'name' => "Employees"], ['name' => "Create"]
        ];
        return view('admin.employees.create',
            ['title' => 'Add Employee',
                'breadcrumbs' => $breadcrumbs,
                'locations' => $data['locations'],
                'departments' => $data['departments'],
                'employment_statuses' => $data['employmentStatus'],
                'designations' => $data['designations'],
                'employees' => $data['employees'],
                'workSchedules' => $data['workSchedules'],
                'permissions' => $data['permissions'],
                'roles' => $data['roles'],
                'locale' => $locale
            ]);

    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function store(validateEmployee $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        //TODO:: also perform js validation
        $stored = (new StoreEmployeeAndAssignAdditionalDetails())->execute($request, $locale);
        if ($stored) {
            Session::flash('success', trans('language.Employee is created succesfully'));
        }
        return redirect($locale . '/employees')->with('locale', $locale);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $id)
    {
        $employee = Employee::where('id', $id)->first();
        if ($employee == null) {
            return response('Employee Not Found', 404);
        }
        return response()->json($employee);
    }

    /**
     * Edit Form For Specific Employee.
     *
     * @param $lang
     * @param $id
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function edit($lang, $id, Request $request)
    {
        $data = (new EditEmployee())->execute($id, $this);
        $locale = $request->segment(1, ''); // `en` or `es`
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "People Management"], ['link' => "$locale/employees", 'name' => "Employees"], ['name' => "Basic Information"]
        ];
        App::setLocale($locale);
        return view('admin.employees.edit',
            [
                'title' => 'Update Employee',
                'breadcrumbs' => $breadcrumbs,
                'perms' => $data['permissions'],
                'workSchedules' => $data['workSchedules'],
                'employee' => $data['info']['employee'],
                'disableFields' => $data['info']['disableFields'],
                'locations' => $data['locations'],
                'designations' => $data['designations'],
                'departments' => $data['departments'],
                'divisions' => $data['divisions'],
                'employment_statuses' => $data['employmentStatus'],
                'jobs' => $data['jobs'],
                'employeeEmploymentStatuses' => $data['employeeEmploymentStatuses'],
                'employees' => $data['employees'],
                'educations' => $data['education'],
                'educationtypes' => $data['info']['educationType'],
                'languages' => $data['info']['secondaryLanguage'],
                'visas' => $data['visa'],
                'VisaTypes' => $data['info']['visaType'],
                'changeReasons' => $data['info']['changeReasons'],
                'paySchedules' => $data['info']['paySchedules'],
                'countries' => $data['info']['country'],
                'roles' => $data['selectedRole'],
                'requestApprovals' => $data['requestApproval'],
                'locale' => $locale,
                'requests' => $data['requests']
            ]);
    }

    /**
     * @param $locale
     * @param Request $request
     * @param $id
     *
     * @return Factory|View
     */
    public function update($locale, storeEmployee $request, $id)
    {
        $data = (new UpdateEmployee())->execute($request->all(), $id);
        if ($data != false) {
            Session::flash('success', trans('language.Employee is updated successfully'));
        }
        return redirect($locale . '/employee/edit/' . $id)->with('locale', $locale);
    }

    /**
     * @param $locale
     * @param Request $request
     * @param $id
     *
     * @return Factory|View
     */
    public function updateSalary(updateSalary $request, $locale, $id)
    {
        (new UpdateEmployeeSalary())->execute($request->all(), $id);
        Session::flash('success', trans('language.Employee salary is updated successfully'));
        return redirect($locale . '/employee/edit/' . $id)->with('locale', $locale);
    }
}
