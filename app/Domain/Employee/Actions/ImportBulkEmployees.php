<?php


namespace App\Domain\Employee\Actions;

use Validator;
use Illuminate\Support\Facades\Schema;
use App\Domain\Employee\Models\Employee;
use App\Domain\Employee\Models\ImportEmployee;
use Illuminate\Support\Facades\Hash;

class ImportBulkEmployees
{
    /**
     * @return mixed
     */
    public function execute($request)
    {
        $temporarydata = ImportEmployee::find(1);
        $employeedata = json_decode($temporarydata->excel_data, true);
        $count = count($employeedata);
        $password = '123456';
        if (!empty($employeedata)) {
           for($i=0; $i<$count; $i++){
                $data = new Employee;
                foreach ($request->fields as  $index => $field) {
                    if($field){
                        $data->$field = $employeedata[$i][$index];
                        $data->password = Hash::make($password);
                    }
                }
                $validator = Validator::make($data->toArray(), [
                    'firstname'                         => 'required|string|max:255',
                    'lastname'                          => 'required|string|max:255',
                    'employee_no'                       => 'unique:employees',
                    'official_email'                    => 'required|string|email|max:255|unique:employees',
                ]);

                if($validator->fails()) {
                    Schema::dropIfExists('import_employees');
                    return $data = ['check' => false, 'validator' => $validator];
                }
                $data->save();
           }
           return $data = ['check' => true, 'validator' => ''];
        }
        else {
            return $data = ['check' => false];
        }
    }
}
