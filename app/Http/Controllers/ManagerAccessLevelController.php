<?php

namespace App\Http\Controllers;

use App;
use App\Domain\ACL\Actions\CreateManagerAccessLevel;
use App\Domain\ACL\Actions\DestroyRole;
use App\Domain\ACL\Actions\DuplicateManagerRole;
use App\Domain\ACL\Actions\EditManagerAccessLevel;
use App\Domain\ACL\Actions\EditManagersRoleAndPermissions;
use App\Domain\ACL\Actions\StoreManagerRoleAndPermissions;
use Illuminate\Http\Request;
use Session;

class ManagerAccessLevelController extends Controller
{
    /**
     * Display a listing of the resource.
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
    public function create($lang, Request $request)
    {
        session()->forget('unauthorized_user');
        $data = (new CreateManagerAccessLevel())->execute();
        $locale = $request->segment(1, ''); // `en` or `es`
        $breadcrumbs = [
            ['link' => "$locale/access-level", 'name' => "Manage Roles"], ['link' => "$locale/access-level", 'name' => "Access Levels"], ['name' => " Create New Manager Access Level"]
        ];
        if ($data['employeeRoles']->isEmpty()) {
            Session::flash('error', trans('Please create employee role'));
            return redirect($lang . '/access-level');
        }
        return view('admin.manager_access_level.create')->with('locale', $lang)
            ->with('defaultPermissions', $data['defaultPermissions'])
            ->with('employeeRoles', $data['employeeRoles'])
            ->with('breadcrumbs', $breadcrumbs);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($locale, Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $result = (new StoreManagerRoleAndPermissions())->execute($request);
        if ($result == false) {
            return redirect()->back()->with('error', 'Access level already exist.');
        }
        return redirect($locale . '/access-level')->with('success', 'Access level created successfully.');
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
     * @return Response
     */
    public function edit($locale, $id)
    {
        $breadcrumbs = [
            ['link' => "$locale/access-level", 'name' => "Manage Roles"], ['link' => "$locale/access-level", 'name' => "Access Levels"], ['name' => "Edit Manager Access Level"]
        ];
        session()->forget('unauthorized_user');
        $data = (new EditManagerAccessLevel())->execute($id);
        return view('admin.manager_access_level.edit')
            ->with('defaultPermissions', $data['defaultPermissions'])
            ->with('role', $data['roleAndAccessLevel']['role'])
            ->with('employeeRoles', $data['employeeRoles'])
            ->with('accessLevel', $data['roleAndAccessLevel']['accessLevel'])
            ->with('locale', $locale)
            ->with('subRoleAccessLevel', $data['subRoleAccessLevel'])
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
        $result = (new EditManagersRoleAndPermissions())->execute($request, $id);
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
     * Create duplicate role
     *FIXME:
     * @param  $locale
     * @param int $id
     * @return Response
     */
    public function duplicate($locale, $id)
    {
        session()->forget('unauthorized_user');
        $role = (new DuplicateManagerRole())->execute($id);
        if ($role != false) {
            return redirect($locale . '/access-level/manager/' . $role . '/edit');
        }
        Session::flash('error', trans('Error occured.'));
        return redirect($locale . '/access-level');
    }
}
