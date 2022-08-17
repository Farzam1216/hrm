<?php

namespace App\Http\Controllers;

use App;
use App\Domain\ACL\Actions\CreateCustomAccessLevel;
use App\Domain\ACL\Actions\DestroyRole;
use App\Domain\ACL\Actions\DuplicateCustomRole;
use App\Domain\ACL\Actions\EditCustomAccessLevel;
use App\Domain\ACL\Actions\EditCustomRoleAndPermissions;
use App\Domain\ACL\Actions\StoreCustomRoleAndPermissions;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;

class CustomAccessLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($lang, Request $request)
    {
        session()->forget('unauthorized_user');
        $data = (new CreateCustomAccessLevel())->execute();
        $locale = $request->segment(1, ''); // `en` or `es`
        $breadcrumbs = [
            ['link' => "$locale/access-level", 'name' => "Manage Roles"], ['link' => "$locale/access-level", 'name' => "Access Levels"], ['name' => " Create New Custom Access Level"]
        ];

        if ($data['employeeRoles']->isEmpty()) {
            Session::flash('error', trans('Please create employee role'));
            return redirect($lang . '/access-level');
        }
        return view('admin.custom_access_level.create')
            ->with('employees', $data['employees'])
            ->with('locale', $lang)
            ->with('defaultPermissions', $data['defaultPermissions'])
            ->with('employeeRoles', $data['employeeRoles'])
            ->with('manageFullPermissions', $data['manageFullPermissions'])
            ->with('breadcrumbs', $breadcrumbs);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $result = (new StoreCustomRoleAndPermissions())->execute($request);
        if ($result == false) {
            return redirect()->back()->with('error', 'Access level already exist.');
        }
        return redirect($locale . '/access-level')->with('success', 'Access level created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($locale, $id)
    {
        $breadcrumbs = [
            ['link' => "$locale/access-level", 'name' => "Manage Roles"], ['link' => "$locale/access-level", 'name' => "Access Levels"], ['name' => "Edit Custom Access Level"]
        ];
        session()->forget('unauthorized_user');
        $data = (new EditCustomAccessLevel())->execute($id);
        return view('admin.custom_access_level.edit')
            ->with('defaultPermissions', $data['defaultPermissions'])
            ->with('role', $data['roleAndAccessLevel']['role'])
            ->with('employees', $data['employees'])
            ->with('accessLevel', $data['roleAndAccessLevel']['accessLevel'])
            ->with('defaultfullAcessPermissions', $data['defaultfullAcessPermissions'])
            ->with('employeeRoles', $data['employeeRoles'])
            ->with('specificEmployees', $data['specificEmployees'])
            ->with('subRoleAccessLevel', $data['subRoleAccessLevel'])
            ->with('locale', $locale)
            ->with('breadcrumbs', $breadcrumbs);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update($locale, Request $request, $id)
    {
        $result = (new EditCustomRoleAndPermissions())->execute($request, $id);
        if ($result == false) {
            return redirect()->back()->with('error', 'Access level already exist.');
        }
        return redirect($locale . '/access-level')->with('success', 'Access level updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($locale, $id)
    {
        session()->forget('unauthorized_user');
        $result = (new DestroyRole())->execute($id);
        if ($result) {
            return redirect()->back()->with('success', 'Role deleted successfuly')->with('locale', $locale);
        } else {
            return redirect()->back()->with('error', 'This role contains employees.Can\'t be deleted')->with('locale', $locale);
        }
    }
    /**
     * create duplicate custom role.
     *
     * @param  int  $id
     * @return Response
     */
    public function duplicate($locale, $id)
    {
        session()->forget('unauthorized_user');
        $role = (new DuplicateCustomRole())->execute($id);
        if ($role != false) {
            return redirect($locale . '/access-level/custom/' . $role . '/edit');
        }
        Session::flash('error', trans('Error occured.'));
        return redirect($locale . '/access-level');
    }
}
