<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\Education;
use App\Domain\Employee\Models\EducationType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Session;

class UpdateEmployeeEducation
{

    /**
     * @param $id
     * @param $data
     * @return array
     */
    public function execute($id, $request)
    {
        try {
            $education = Education::find($id);
            $data = $request->all();
            array_shift($data);
            if (Auth::id() == $data['employee_id']) {
                $changedFields = (new SaveEmployeeEducationChangeRequest())->execute($id, $data);
            }
            foreach ($data as $field => $value) {
                if ( isset($changedFields) && count($changedFields) > 0) {
                    if (in_array($field, $changedFields)) {
                        continue;
                    }
                } else {
                    $education->{$field} = $value;
                }
            }
            $education->save();
            $education->education_type = EducationType::find($education->education_type_id);
            
            if (isset($changedFields) && count($changedFields) > 0) {
                Session::flash('success', trans('language.Employee Education requested succesfully'));
            } else {
                Session::flash('success', trans('language.Employee Education is Updated successfully'));
            }
            return $education;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Session::flash('error', trans('language.Failed to Update Employee Education.'));
        }
    }
}
