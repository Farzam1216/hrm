<?php

namespace App\Http\Controllers;

use App;
use App\Domain\ACL\Actions\EditAccessLevel;
use App\Domain\ACL\Actions\ViewAccessLevel;
use Illuminate\Http\Request;

class AccessLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "$locale/access-level", 'name' => "Manage Roles"], ['name' => "Access Levels"]
        ];
        $data = (new ViewAccessLevel())->execute();

        return view('admin.access_level.index')->with('locale', $locale)
            ->with('accessLevelEmployees', $data['accessLevelEmployees'])
            ->with('accessLevelManager', $data['accessLevelManager'])
            ->with('accessLevelCustom', $data['accessLevelCustom'])
            ->with('accessLevelHrPro', $data['accessLevelHrPro'])
            ->with('noAccessEmployees', $data['noAccessEmployees'])
            ->with('accessLevelAdmin', $data['accessLevelAdmin'])
            ->with('employeeRoles', $data['employeeRoles'])
            ->with('managerRoles', $data['managerRoles'])
            ->with('customRoles', $data['customRoles'])
            ->with('hrProRoles', $data['hrProRoles'])
            ->with('adminRole', $data['adminRole'])
            ->with('breadcrumbs', $breadcrumbs);
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
    public function edit($lang, Request $request, $id)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $data = (new EditAccessLevel())->execute($id);
        $breadcrumbs = [
            ['link' => "$locale/access-level", 'name' => "Manage Roles"], ['name' => "Access Levels"]
        ];
        return view('admin.access_level.edit')->with('locale', $locale)
            ->with('employeeRoles', $data['employeeRoles'])
            ->with('managerRoles', $data['managerRoles'])
            ->with('employeeRole', $data['employee']->roles->pluck('name')->first())
            ->with('customRoles', $data['customRoles'])
            ->with('breadcrumbs', $breadcrumbs);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
}
