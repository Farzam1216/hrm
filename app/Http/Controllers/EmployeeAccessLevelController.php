<?php

namespace App\Http\Controllers;

use App;
use App\Domain\ACL\Actions\DestroyRole;
use App\Domain\ACL\Actions\DuplicateEmployeeRole;
use App\Domain\ACL\Actions\EditEmployeeAccessLevel;
use App\Domain\ACL\Actions\EditEmployeeRoleAndPermissions;
use App\Domain\ACL\Actions\GetDefaultPermissions;
use App\Domain\ACL\Actions\StoreEmployeeRoleAndPermissions;
use App\Domain\ACL\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;

class EmployeeAccessLevelController extends Controller
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
    public function create($locale)
    {
        $breadcrumbs = [
            ['link' => "$locale/access-level", 'name' => "Manage Roles"], ['link' => "$locale/access-level", 'name' => "Access Levels"], ['name' => " Create New Employee Access Level"]
        ];
        session()->forget('unauthorized_user');
        $defaultPermissions = (new GetDefaultPermissions)->execute("employee");
        return view('admin.employee_access_level.create')->with('locale', $locale)->with('defaultPermissions', $defaultPermissions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function store($locale, Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $result = (new StoreEmployeeRoleAndPermissions())->execute($request);
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
            ['link' => "$locale/access-level", 'name' => "Manage Roles"], ['link' => "$locale/access-level", 'name' => "Access Levels"], ['name' => "Edit Employee Access Level"]
        ];
        session()->forget('unauthorized_user');
        $data = (new EditEmployeeAccessLevel())->execute($id);
        return view('admin.employee_access_level.edit')
            ->with('defaultPermissions', $data['defaultPermissions'])
            ->with('role', $data['role'])
            ->with('canRequest', $data['canRequest'])
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
        $result = (new EditEmployeeRoleAndPermissions())->execute($request, $id);
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
            return redirect()->back()->with('success', 'Role deleted successfully')->with('locale', $locale);
        } else {
            return redirect()->back()->with('error', 'This role contains employees.Can\'t be deleted')->with('locale', $locale);
        }
    }

    /**
     * Create duplicate employee role
     *FIXME:
     * @param  string  $locale
     * @param  int  $id
     * @return Response
     */
    public function duplicate($locale, $id)
    {
        session()->forget('unauthorized_user');
        $role = (new DuplicateEmployeeRole())->execute($id);
        if ($role != false) {
            return redirect($locale . '/access-level/employee/' . $role . '/edit');
        }
        Session::flash('error', trans('Error occured.'));
        return redirect($locale . '/access-level');
    }
}
