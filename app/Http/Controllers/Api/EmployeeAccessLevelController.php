<?php

namespace App\Http\Controllers\Api;

use App;
use Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Domain\ACL\Models\Role;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Domain\ACL\Actions\DestroyRole;
use App\Domain\ACL\Actions\DuplicateEmployeeRole;
use App\Domain\ACL\Actions\GetDefaultPermissions;
use App\Domain\ACL\Actions\EditEmployeeAccessLevel;
use App\Domain\Employee\Actions\GetEmployeeTypeRoles;
use App\Domain\ACL\Actions\EditEmployeeRoleAndPermissions;
use App\Domain\ACL\Actions\StoreEmployeeRoleAndPermissions;

class EmployeeAccessLevelController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $employeeRoles = (new GetEmployeeTypeRoles())->execute();
        if (!$employeeRoles->isEmpty()) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Employee Roles has been Recieved.";
            $this->responseData['data'] = $employeeRoles;
            $this->status = 200;
        }
        return $this->apiResponse();
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
        return redirect()->back()->with('success', 'Access level created successfully.');
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update($lang, Request $request, $id)
    {
        $result = (new EditEmployeeRoleAndPermissions())->execute($request, $id);
        if ($result == false) {
            return redirect()->back()->with('error', 'Access level already exist.');
        }
        return redirect()->back()->with('success', 'Access level updated successfully.');
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
            return redirect()->back()->with('success', 'language.Role deleted successfuly')->with('locale', $locale);
        } else {
            return redirect()->back()->with('error', 'language.This role contains employees.Can\'t be deleted')->with('locale', $locale);
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
