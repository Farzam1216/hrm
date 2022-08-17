<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\Education;
use App\Domain\Employee\Models\EducationType;
use Illuminate\Support\Facades\Log;
use Session;

class StoreEmployeeEducation
{
    public function execute($request,$id)
    {
        try {
            $education = new Education();
            $education->employee_id = $request->employee_id;
            if(isset($request->institute_name)) {
                $education->institute_name = $request->institute_name;
            }
            if(isset($request->education_type_id)) {
                $education->education_type_id = $request->education_type_id;
            }
            if(isset($request->major)) {
                $education->major = $request->major;
            }
            if(isset($request->cgpa)) {
                $education->cgpa = $request->cgpa;
            }
            if(isset($request->date_start)) {
                $education->date_start = $request->date_start;
            }
            if(isset($request->date_end)) {
                $education->date_end = $request->date_end;
            }
            if (empty($education->institute_name) && empty($education->education_type_id)
                && empty($education->major)) {
                unset($education);
            } else {
                $education->save();

                $education->education_type = EducationType::find($education->education_type_id);
                return $education;
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }
}
