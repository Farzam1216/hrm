<?php


namespace App\Domain\Hiring\Actions;

use App\Domain\Hiring\Models\Question;
use App\Domain\Employee\Models\Employee;
use App\Domain\Employee\Models\Location;
use App\Domain\Employee\Models\Department;
use App\Domain\Employee\Models\Designation;
use App\Domain\Employee\Models\EmploymentStatus;



class CreateJobOpening
{
    /**
     * @param 
     * create job opening
     */
    public function execute()
    {
        $data['designations'] = Designation::all();
        $data['departments'] = Department::all();
        $data['locations'] = Location::all();
        $data['employees'] = Employee::all();
        $data['employmentStatus'] = EmploymentStatus::all();
        $data['questions'] = Question::all();
        $data['que'] = Question::where('type_id', 1)->get();
        return $data;
    }
}
