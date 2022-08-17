<?php

namespace App\Http\Controllers;

use App\Domain\ACL\Actions\AssignMultipleRoles;
use App\Domain\ACL\Actions\CreateNonEmployeeUser;
use App\Domain\ACL\Actions\EditAccessLevelUser;
use App\Domain\ACL\Actions\StoreNonEmployee;
use App\Domain\ACL\Actions\UpdateAccessLevelUser;
use App\Http\Requests\validateNonEmployeeUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Session;

class AccessLevelUserController extends Controller
{
    /**
     * Display a listing of the resource
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function edit($locale, $id)
    {
        session()->forget('unauthorized_user');
        $data = (new EditAccessLevelUser())->execute($id);
        $breadcrumbs = [
            ['link' => "$locale/access-level", 'name' => "Manage Roles"], ['name' => "Add Employee"]
        ];
        return view('admin.access_level.addemployee')->with('locale', $locale)
            ->with('selectedEmployees', $data['selectedEmployees'])
            ->with('role', $data['role'])
            ->with('availableEmployees', $data['availableEmployees'])
            ->with('breadcrumbs', $breadcrumbs);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($locale, Request $request, $roleId, $employeeId = null)
    {
        (new UpdateAccessLevelUser())->execute($request, $roleId, $employeeId);
        return redirect($locale . '/access-level')->with('locale', $locale);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    /**
     * return add non employee view
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function createNonEmployeeUser(Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Manage Role"], ['name' => "Access Level"], ['name' => "Add Non Employee"]
        ];
        $data = (new CreateNonEmployeeUser())->execute();
        $breadcrumbs = [
            ['link' => "$locale/access-level", 'name' => "Manage Roles"], ['name' => "Add Non Employee"]
        ];
        return view('admin.access_level.addNonEmployeeUser')->with('locale', $locale)
            ->with('managerRoles', $data['managerRoles']['roles'])
            ->with('customRoles', $data['customRoles']['roles'])
            ->with('breadcrumbs', $breadcrumbs);
    }
    /**
     * Store non employee Users
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function storeNonEmployeeUser(validateNonEmployeeUser $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $user = (new StoreNonEmployee())->execute($request);
        Session::flash('success', trans('Non Employee User ' . $user->firstname . ' ' . $user->lastname . ' has been created'));
        return redirect($locale . '/access-level')->with('locale', $locale);
    }
    /**
     * Assign multiple roles to user
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function assignMultipleRoles(Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $result = (new AssignMultipleRoles())->execute($request);
        if ($result) {
            Session::flash('success', trans('Employee Added successfully in the Role(s)'));
        } else {
            Session::flash('error', trans('Unable to add employee(s) in role(s)'));
        }
        return redirect($locale . '/access-level')->with('locale', $locale);
    }
}
