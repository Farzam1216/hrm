<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Approval\Actions\CompareRequestedFieldWithFillable;
use App\Domain\Employee\Models\Employee;
use App\Domain\Employee\Actions\storeEmployeeNotifications;
use App\Domain\Employee\Models\EmployeeBankAccount;
use App\Traits\AccessibleFields;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session;

class UpdateEmployee
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
        if(isset($data['work-schedule-id'])) {
            $employee = Employee::find($id);
            $employee->work_schedule_id = $data['work-schedule-id'];
            $employee->save();
            array_shift($data);
        }
        $previous_info =  Employee::find($id);
        (new storeEmployeeNotifications())->execute($data,$previous_info);

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
            if (isset($data['employee_no']) && $data['employee_no'] != $employee->employee_no) {
                $employee_no = Employee::where('employee_no', $data['employee_no'])->first();
                if ($employee_no) {
                    Session::flash('error', trans('The employee no has already been taken.'));
                    return false;
                }
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
            
            if (!empty($data['basic_salary'])) {
                $employee->basic_salary = $data['basic_salary'];
            }

            if (!empty($data['home_allowance'])) {
                $employee->home_allowance = $data['home_allowance'];
            }

            if (!empty($data['manager_id'])) {
                $employee->manager_id = $data['manager_id'];
            }
            
            if (!empty($data['can_mark_attendance'])) {
                $employee->can_mark_attendance = $data['can_mark_attendance'];
            }
        }

        $employee->save();
        // FIXME:: Bank Account details not found in bamboohr
//        //Comparing Bank Account Info
//        $employeeBankInfo = new EmployeeBankAccount();
//        $employeeBankDetails = $employeeBankInfo->getFillable();
//        $employeeBankDetails = (new CompareRequestedFieldWithFillable())->execute(
//            $requestedData,
//            $employeeBankDetails
//        );
//
//        if (EmployeeBankAccount::where('employee_id', $id)->first() == null) {
//            $bank = new EmployeeBankAccount();
//            $bank->employee_id = $id;
//            foreach ($employeeBankDetails as $bankField => $info) {
//                $bank->{$bankField} = $info;
//            }
//            $bank->save();
//        } else {
//            $bank = EmployeeBankAccount::where('employee_id', $id)->first();
//
//            foreach ($employeeBankDetails as $bankField => $info) {
//                $bank->{$bankField} = $info;
//            }
//            $bank->save();
//        }

        if (count($changedFields) > 0) {
            Session::flash('success', trans('language.Employee data requested succesfully'));
        } else {
            Session::flash('success', trans('language.Employee is updated succesfully'));
        }

        return $employee;
    }
}
