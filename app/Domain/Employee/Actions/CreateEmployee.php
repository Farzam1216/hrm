<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\Employee;
use Illuminate\Support\Facades\Log;
use Session;

class CreateEmployee
{

    //Old Name:: StoreEmployeeInformation
    /**
     * @param $data
     * @return mixed
     */
    public function execute($data)
    {
        try {
            $password = bcrypt('123456');
            $requestedData = [
                'employee_no' => $data['employee_no'],
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'contact_no' => $data['contact_no'],
                'emergency_contact' => $data['emergency_contact'],
                'emergency_contact_relationship' => $data['emergency_contact_relationship'],
                'password' => $password,
                'official_email' => $data['official_email'],
                'personal_email' => $data['personal_email'],
                'status' => 1,
                'employment_status_id' => $data['employment_status'],
                'department_id' => $data['department_id'],
                'designation_id' => $data['designation'],
//                'basic_salary' => $data['basic_salary'],
//                'home_allowance' => $data['home_allowance'],
                // 'type' => $data['type'],
                'nin' => $data['nin'],
                'date_of_birth' => $data['date_of_birth'],
                'location_id' => $data['location_id'],
                'current_address' => $data['current_address'],
                'permanent_address' => $data['permanent_address'],
                'city' => $data['city'],
                'joining_date' => $data['joining_date'],
                'manager_id' => $data['manager_id'],
                'gender' => $data['gender'],
                'marital_status' => $data['marital_status'],
                'work_schedule_id' => $data['workscheduleid'],
            ];

            if ($data['picture'] != '') {;  
                $picture = time() . '_' . $data['picture']->getClientOriginalName();
                $data->picture->move('storage/employees/profile/', $picture);
                $arr['picture'] = $picture;
                $requestedData = array_merge($requestedData,['picture' => 'storage/employees/profile/' . $arr['picture']]);
            }

            $employee = Employee::create($requestedData);
            if (isset($data->employeerole)) {
                (new AssignRoleToNewEmployee())->execute($data->employeerole, $employee);
            }
            return $employee;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Session::flash('error', trans('language.Failed to Create Employee.'));
        }
    }
}
