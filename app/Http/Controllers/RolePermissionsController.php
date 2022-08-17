<?php

namespace App\Http\Controllers;

use App;
use App\Domain\ACL\Models\Permission;
use App\Domain\ACL\Models\Role;
use App\Domain\Employee\Models\Employee;
use App\Http\Requests\storeRolePermissions;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Session;

class RolePermissionsController extends Controller
{
    /**
     * Show Roles List.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $roles = Role::all();
        $permissions = Permission::all();

        return view('admin.roles_permissions.index')->with([
            'roles' => $roles,
            'permissions' => $permissions,
            'locale' => $locale,
        ]);
    }

    /**
     * Assign Role  To Employee.
     *
     * @return Response
     */
    public function applyRole()
    {
        $roles = Role::all();
        $employees = Employee::all();
        $permissions = Permission::all();

        return view('admin.roles_permissions.applyRole')->with([
            'roles' => $roles,
            'employees' => $employees,
        ]);
    }

    public function applyRolePost(Request $request)
    {
        $role = Role::find($request->role_id);
        $employee = Employee::find($request->employee_id);
        $employee->assignRole($role);

        return redirect()->route('roles_permissions')->with(
            'success',
            'Role ('.$role->name.') Assigned employee ('.$employee->firstname.' '.$employee->lastname.') succesfully'
        );
    }

    /**
     * Get Permission List From Role.
     *
     * @return Response
     */
    public function getPermissionsFromRole($id, $employee_id)
    {
        $emp_permissions = Employee::find($employee_id)->permissions()->get()->pluck('id')->toArray();
        $role = Role::find($id);

        $permissions = $role->permissions()->get();
        $routes = [];

        foreach ($permissions as $key => $permission) {
            $index = explode(':', $permission->name);
            $routes[$index[0]][] = $permission;
        }

        return view('admin.roles_permissions.getPermissionsFromRole')->with([
            'role' => $role,
            'all_controllers' => $routes,
            'emp_permissions' => $emp_permissions,
        ]);
    }

    /**
     * Show Create New Role Form.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        return view('admin.roles_permissions.create')->with([
            'all_controllers' => $this->routesList(),
            'locale' => $locale,
        ]);
    }

    /**
     * Store New Role In Storage.
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws ValidationException
     */
    public function store(storeRolePermissions $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $role = Role::create(['name' => $request->name]);
        if ($request->permissions) {
            foreach ($request->permissions as $value) {
                $val = explode(':', $value);
                $data = [
                    'guard_name' => $val[0],
                    'name' => $val[1].':'.$val[2],
                ];

                $permission = Permission::where($data)->first();
                if (!isset($permission->id)) {
                    $permission = Permission::create($data);
                }
                $role->givePermissionTo($permission);
            }
        }
        Session::flash('success', trans('language.Role is created successfully'));

        return redirect($locale.'/rolespermissions')->with('locale', $locale);
    }

    /**
     * Show Form For Edit Role Of Specific ID.
     *
     * @param int     $id
     * @param Request $request
     *
     * @return Response
     */
    public function edit($id, Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $role = Role::find($id);
        $permissions = $role->permissions()->get()->toArray();
        $routes = [];
        foreach ($permissions as $permission) {
            $routes[] = $permission['name'];
        }
        return view('admin.roles_permissions.edit')->with([
            'role' => $role,
            'permissions' => Permission::all(),
            'locale' => $locale,
        ]);
        // return view('admin.roles_permissions.edit')->with([
        //     'role' => $role,
        //     'routes' => $routes,
        //     'all_controllers' => $this->routesList(),
        //     'locale' => $locale,
        // ]);
    }
    

    /**
     * Get Route List.
     *
     * @return array
     */
    public function routesList()
    {
        $all_controllers = [];
        foreach (Route::getRoutes()->getRoutes() as $route) {
            $action = $route->getAction();
            if (array_key_exists('controller', $action)) {
                $row = explode('@', $action['controller']);
                $index = str_replace("App\Http\Controllers\\", '', $row[0]);
                $index = str_replace('Auth\\', '', $index);
                if ($index == 'LoginController' ||
                    $index == 'RegisterController' ||
                    $index == 'ForgotPasswordController' ||
                    $index == 'ResetPasswordController' ||
                    $index == '\Laravel\Passport\Http\Controllers\AuthorizationController' ||
                    $index == '\Laravel\Passport\Http\Controllers\ApproveAuthorizationController' ||
                    $index == '\Laravel\Passport\Http\Controllers\DenyAuthorizationController' ||
                    $index == '\Laravel\Passport\Http\Controllers\AccessTokenController' ||
                    $index == '\Laravel\Passport\Http\Controllers\AuthorizedAccessTokenController' ||
                    $index == '\Laravel\Passport\Http\Controllers\TransientTokenController' ||
                    $index == '\Laravel\Passport\Http\Controllers\ClientController' ||
                    $index == '\Laravel\Passport\Http\Controllers\ScopeController' ||
                    $index == '\Laravel\Passport\Http\Controllers\PersonalAccessTokenController' ||
                    $index == 'ApiAuthController' ||
                    $index == 'Api\EmployeeController' ||
                    $index == 'Api\EmployeeDocumentController' ||
                    $index == 'Api\EmployeeAssetsController' ||
                    $index == 'Api\EmployeeNotesController' ||
                    $index == 'Api\BranchesController' ||
                    $index == 'Api\DepartmentsController' ||
                    $index == 'Api\DesignationsController' ||
                    $index == 'Api\AttendanceController' ||
                    $index == 'Api\AttendanceBreaksController' ||
                    $index == 'Api\AttendanceBreaksController' ||
                    $index == 'Api\LeaveController' ||
                    $index == 'Api\LeaveTypeController'
                ) {
                    continue;
                }
                $all_controllers[$index][] = $row[1];
            }
        }
        $controllers = [];
        foreach ($all_controllers as $key => $all_controller) {
            $unique_controllers = array_unique($all_controller);
            $controllers[$key] = $unique_controllers;
        }

        return $controllers;
    }

    /**
     * Get Route List Of Employees.
     *
     * @return Response
     */
    public function routesListForEmp()
    {
        $all_controllers = [];

        foreach (Route::getRoutes()->getRoutes() as $route) {
            $action = $route->getAction();
            if (array_key_exists('controller', $action)) {
                $row = explode('@', $action['controller']);
                $index = str_replace("App\Http\Controllers\\", '', $row[0]);
                $index = str_replace('Auth\\', '', $index);
                if ($index == 'LoginController' ||
                    $index == 'RegisterController' ||
                    $index == 'ForgotPasswordController' ||
                    $index == 'ResetPasswordController'
                ) {
                    continue;
                }
                $all_controllers[$index][] = $row[1];
            }
        }

        return $all_controllers;
    }

    /**
     * Update Role In Storage.
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $role = Role::find($id);
        $role->name = $request->name;
        $role->save();
        if ($request->permissions_checked == null) {
            $request->permissions_checked = [];
        }
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        if ($request->permissions) {
            foreach ($request->permissions as $value) {
                $val = explode(':', $value);
                $data = [
                    'guard_name' => $val[0],
                    'name' => $val[1].':'.$val[2],
                ];
                
                $permission = Permission::where($data)->first();
                if (in_array($value, $request->permissions_checked)) {
                    if (!isset($permission->id)) {
                        $permission = Permission::create($data);
                        $role->givePermissionTo($permission);
                    } else {
                        $role->givePermissionTo($permission);
                    }
                } else {
                    $role->revokePermissionTo($permission);
                }
            }
        }

        return redirect($locale.'/rolespermissions')->with('success', 'Role is updated successfully')->with('locale', $locale);
    }

    /**
     * Remove the Role from storage.
     *
     * @param int     $id
     * @param Request $request
     *
     * @return Response
     */
    public function destroy($id, Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $role = Role::find($id);
        foreach ($role->permissions as $key => $permission) {
            $role->revokePermissionTo($permission);
        }
        $role->delete();

        return redirect()->back()->with('success', 'Role and assigned permissions is deleted successfully.')->with('locale', $locale);
    }
}
