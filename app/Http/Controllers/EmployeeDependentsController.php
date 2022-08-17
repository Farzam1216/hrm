<?php

namespace App\Http\Controllers;

use App;
use App\Domain\Benefit\Actions\AddEmployeeDependents;
use App\Domain\Benefit\Actions\CreateEmployeeDependent;
use App\Domain\Benefit\Actions\DeleteEmployeeDependents;
use App\Domain\Benefit\Actions\EditEmployeeDependentsWithPermissions;
use App\Domain\Benefit\Actions\UpdateEmployeeDependents;
use App\Domain\Benefit\Actions\ViewEmployeeDependents;
use App\Domain\Employee\Actions\StoreEmployeeDependentsNotification;
use App\Domain\Employee\Actions\StoreEmployeeDependentsUpdateNotification;
use App\Domain\Employee\Actions\StoreEmployeeDependentsDeleteNotification;
use App\Domain\Employee\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class EmployeeDependentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        $employeeID = $request->segment(3, Auth::id());
        App::setLocale($locale);
        $data = (new ViewEmployeeDependents())->execute($employeeID, $this);
        if ($employeeID == Auth::user()->id) {
            $breadcrumbs = [
                ['name' => "My Info"], ['name' => "Dependents"]
            ];
        } else {
            $employee = Employee::find($employeeID);
            $breadcrumbs = [
                ['name' => $employee->getFullNameAttribute()], ['name' => "Dependents"]
            ];
        }

        return view('admin.employee_dependents.index')
            ->with('employeeDependents', $data['employeeDependents'])
            ->with('locale', $locale)
            ->with('breadcrumbs', $breadcrumbs)
            ->with('emp_id', $employeeID)
            ->with('permissions', $data['permissions']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        $employeeID = $request->segment(3, Auth::id());
        $data = (new CreateEmployeeDependent())->execute($employeeID, $this);
        App::setLocale($locale);
        if ($employeeID == Auth::user()->id) {
            $breadcrumbs = [
                ['name' => "My Info"], ['link' => "$locale/employees/$employeeID/dependents",'name' => "Dependents"], ['name' => "Create Dependents"]
            ];
        } else {
            $employee = Employee::find($employeeID);
            $breadcrumbs = [
                ['name' => $employee->getFullNameAttribute()], ['link' => "$locale/employees/$employeeID/dependents",'name' => "Dependents"], ['name' => "Create Dependents"]
            ];
        }
        return view('admin.employee_dependents.create')
            ->with('locale', $locale)
            ->with('countries', $data['country'])
            ->with('emp_id', $employeeID)
            ->with('breadcrumbs', $breadcrumbs)
            ->with('permissions', $data['permissions']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store($lang, $employeeID, Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $validatedData = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
        ]);
        (new AddEmployeeDependents)->execute($employeeID, $request->all());
        (new StoreEmployeeDependentsNotification)->execute($request,$employeeID);
        Session::flash('success', trans('language.Employee Dependent Added successfully'));

        return redirect($locale . '/employees/' . $employeeID . '/dependents')->with('locale', $locale)->with('emp_id', $employeeID);
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
    public function edit($lang, Request $request, $employeeID, $employeeDependentId)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $response = (new EditEmployeeDependentsWithPermissions)->execute($employeeDependentId, $employeeID, $this);
        if ($response == null) {
            return redirect($locale . '/employees/' . $employeeID . '/dependents');
            Session::flash('error', trans('Wrong Dependent Selected!.'));
        }
        if ($employeeID == Auth::user()->id) {
            $breadcrumbs = [
                ['name' => "My Info"], ['link' => "$locale/employees/$employeeID/dependents",'name' => "Dependents"], ['name' => "Update Dependents"]
            ];
        } else {
            $employee = Employee::find($employeeID);
            $breadcrumbs = [
                ['name' => $employee->getFullNameAttribute()], ['link' => "$locale/employees/$employeeID/dependents",'name' => "Dependents"], ['name' => "Update Dependents"]
            ];
        }
        return view('admin.employee_dependents.edit')->with('locale', $locale)
            ->with('employeeDependent', $response['employeeDependent'])
            ->with('countries', $response['countries'])
            ->with('disabledFields', $response['disabledFields'])
            ->with('emp_id', $employeeID)
            ->with('breadcrumbs', $breadcrumbs)
            ->with('permissions', $response['permissions']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $lang
     * @param $employeeID
     * @param \Illuminate\Http\Request $request
     * @param $dependentID
     * @return \Illuminate\Http\Response
     */
    public function update($lang, $employeeID, Request $request, $dependentID)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $validatedData = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
        ]);
        (new StoreEmployeeDependentsUpdateNotification)->execute($request,$employeeID, $dependentID);
        (new UpdateEmployeeDependents)->execute($employeeID, $dependentID, $request->all());

        Session::flash('success', trans('language.Employee Dependent Updated successfully'));
        return redirect($locale . '/employees/' . $employeeID . '/dependents')->with('locale', $locale)->with('emp_id', $employeeID);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($lang, $employeeID, Request $request, $id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new StoreEmployeeDependentsDeleteNotification)->execute($request,$employeeID, $id);
        (new DeleteEmployeeDependents)->execute($id);
        Session::flash('success', trans('language.Employee Dependent Deleted successfully'));
        return redirect($locale . '/employees/' . $employeeID . '/dependents')->with('locale', $locale)->with('emp_id', $employeeID);
    }
}
