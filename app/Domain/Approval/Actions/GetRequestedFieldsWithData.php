<?php


namespace App\Domain\Approval\Actions;

use App\Domain\Benefit\Models\EmployeeDependent;
use App\Domain\Employee\Models\Asset;
use App\Domain\Employee\Models\Department;
use App\Domain\Employee\Models\Education;
use App\Domain\Employee\Models\Employee;
use App\Domain\Employee\Models\EmployeeVisa;
use App\Domain\Employee\Models\EmploymentStatus;
use App\Domain\Employee\Models\Location;
use App\Domain\Employee\Models\Note;
use App\Models\Country;

class GetRequestedFieldsWithData
{
    private $allModels = [
        'EmploymentStatus' => EmploymentStatus::class,
        'Employee' => Employee::class,
        'Location' => Location::class,
        'Department' => Department::class,
        'EmployeeDependent' => EmployeeDependent::class,
        'EmployeeVisa' => EmployeeVisa::class,
        'Note' => Note::class,
        'Asset' => Asset::class,
        'Country' => Country::class,
        'Education' => Education::class,
    ];
    /**
     * @param $formFields
     * @param $id
     *
     * @return null
     */
    public function execute($formFields, $id)
    {
        $requestChangeApprovals = null;
        foreach ($formFields as $group => $fields) {
            foreach ($fields as $field => $details) {
                $IDs = null;
                if ((array_key_exists('content', $details) && $details['content'] == null) || (isset($details['content']['fixed']) && $details['content']['fixed'] == '1')) {
                    //                    if ($details['model'] == 'Employee') {
                    //                         $selectedValue= Employee::where('id', $id)->pluck($field)->first();
                    //
                    //                    } else {
                    //                        $details['selected'] = $this->allModels[$details['model']]::where('employee_id', $id)->pluck($field)->first();
                    //
                    //                    }
                    //                }
                    if ($details['model'] == 'Employee') {
                        $currentID = Employee::where('id', $id)->pluck('id')->first();
                        $IDs[] = $currentID;

                        $details['selected'] = ['id' => $currentID, 'model' => $details['model'], 'value' => Employee::where('id', $id)->pluck($field)->first()];
                        $selectedFields[$currentID] = $details;
                    } else {
                        $selectedValues = $this->allModels[$details['model']]::where('employee_id', $id)->get();
                        foreach ($selectedValues as $selectedValue) {
                            $currentID = $this->allModels[$details['model']]::where('id', $selectedValue->id)->pluck('id')->first();
                            $IDs[] = $currentID;
                            $details['selected'] = ['id' => $currentID, 'model' => $details['model'], 'value' => $this->allModels[$details['model']]::where('id', $selectedValue->id)->pluck($field)->first()];
                            $selectedFields[$currentID] = $details;
                        }
                    }
                }

                if (isset($details['content']['fixed']) && $details['content']['fixed'] == '0') {
                    $options = explode(' ', $details['content']['options']);
                    $model_field = $options[0];
                    $model = end($options);
                    $details['content']['options'] = $this->allModels[$model]::all()->pluck($model_field, 'id');
                    if ($details['model'] == 'Employee') {
                        $currentID = Employee::where('id', $id)->pluck('id')->first();
                        $IDs[] = $currentID;
                        $details['selected'] = ['id' => $currentID, 'model' => $details['model'], 'value' => Employee::where('id', $id)->pluck($field)->first()];
                        $selectedFields[$currentID] = $details;
                    } else {
                        $selectedValues = $this->allModels[$details['model']]::where('employee_id', $id)->get();
                        foreach ($selectedValues as $selectedValue) {
                            $currentID = $this->allModels[$details['model']]::where('id', $selectedValue->id)->pluck('id')->first();
                            $IDs[] = $currentID;
                            $details['selected'] = ['id' => $currentID, 'model' => $details['model'], 'value' => $this->allModels[$details['model']]::where('id', $selectedValue->id)->pluck($field)->first()];
                            $selectedFields[$currentID] = $details;
                        }
                    }
                }
                if (isset($IDs)) {
                    foreach ($IDs as $i) {
                        $requestChangeApprovals[$group][$i][$field] = $selectedFields[$i];
                    }
                } else {
                    $requestChangeApprovals[$group][0][$field] = $details;
                }
            }
        }
        return $requestChangeApprovals;
    }
}
