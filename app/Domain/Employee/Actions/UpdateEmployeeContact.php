<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Approval\Actions\CompareRequestedFieldWithFillable;
use App\Domain\Employee\Models\Employee;
use App\Domain\Employee\Models\EmployeeBankAccount;
use App\Traits\AccessibleFields;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session;

class UpdateEmployeeContact
{
    use AccessibleFields;
    /**
     * Update Employee Of Specific ID.
     *
     * @param Request $request
     * @param $id
     *
     * @return RedirectResponse
     */
    public function execute($data, $id)
    {
        array_shift($data);
        $requestedData = $data;
        $roles = $this->getRoles(Auth::user());
        $employeeRoles = (new GetAllEmployeeRoles())->execute($roles);
        $currentUser = Auth::user();
        $changedFields = [];
        if (!$currentUser->isAdmin()) {
            if ($currentUser->id == $id) {
                (new StoreEmployeeInformationUpdateRequest())->execute($id, $data, $employeeRoles[0]);
            }
        }
        $adminPassword = Auth::user()->password;
        $employee = Employee::find($id);
        $employeePersonalDetails = $employee->getFillable();
        $data = (new CompareRequestedFieldWithFillable())->execute($data, $employeePersonalDetails);
        $data = array_diff_assoc($data, $changedFields);
         //Update Employee
         foreach ($data as $field => $value) {
            if (!empty($data['password']) && $field == 'password' && $data['password'] != null) {
                $employee->password = Hash::make($data['password']);
                continue;
            }
            $employee->{$field} = $value;
    
            if (isset($data['picture']) && 'picture' == $field) {
                $picture = time() . '_' . $data['picture']->getClientOriginalName();
                $picture = time() . '_' . $data['picture']->getClientOriginalName();
                $data['picture']->move('storage/employees/profile/', $picture);
                $employee->picture = 'storage/employees/profile/' . $picture;
            }
            if (!empty($data['password']) && 'password' == $field && null != $data['password']) {
                $employee->password = Hash::make($data['password']);
            }
            if ('employment_status_id' == $field && (strtolower('Terminated') == $data['employment_status_id'] || strtolower('Resigned') == $data['employment_status_id'])) {
                $employee->status = 0;
            }

            // (new SendUpdatedAccountDetailsToEmployee())->execute($employee, $data, $locale);

            if (!empty($data['role_id'])) {
                $role = Role::find($data['role_id']);
                $employee->roles()->detach();
                $employee->assignRole($role);
            }
        }
        $employee->save();
        if (count($changedFields) > 0) {
            Session::flash('success', trans('language.Employee data requested succesfully'));
        } else {
            Session::flash('success', trans('language.Employee Contact is updated succesfully'));
        }

        return $employee;
    }
}
