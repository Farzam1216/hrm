<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\EmployeeDependent;

class AddEmployeeDependents
{
    /**
     * @param $data
     */
    public function execute($emp_id, $data)
    {
        $employeeDependent = new EmployeeDependent();
        $employeeDependent->employee_id = $emp_id;
        $employeeDependent->first_name = $data['first_name'];
        $employeeDependent->middle_name = $data['middle_name'];
        $employeeDependent->last_name = $data['last_name'];
        $employeeDependent->date_of_birth = $data['date_of_birth'];
        $employeeDependent->SSN = $data['SSN'];
        $employeeDependent->SIN = $data['SIN'];
        $employeeDependent->relationship = $data['relationship'];
        $employeeDependent->gender = $data['gender'];
        //        $address=[
        //            'street1'=>$data['street1'] ,
        //            'street2'=>$data['street2'] ,
        //            'city'   =>$data['city'] ,
        //            'state'  =>$data['state'] ,
        //            'zip'    =>$data['zip'] ,
        //            'country'=>$data['country'] ,
        //        ];
        $employeeDependent->street1 = $data['street1'];
        $employeeDependent->street2 = $data['street2'];
        $employeeDependent->city = $data['city'];
        $employeeDependent->state = $data['state'];
        $employeeDependent->zip = $data['zip'];
        $employeeDependent->country = $data['country'];
        if (isset($data['is_us_citizen'])) {
            $employeeDependent->is_us_citizen = $data['is_us_citizen'];
        } else {
            $employeeDependent->is_us_citizen = 0;
        }
        if (isset($data['is_student'])) {
            $employeeDependent->is_student = $data['is_student'];
        } else {
            $employeeDependent->is_student = 0;
        }
        $employeeDependent->home_phone = $data['home_number'];
        $employeeDependent->save();
    }
}
