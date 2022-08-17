<?php


namespace App\Domain\Hiring\Actions;

use App\Domain\Hiring\Models\Question;
use App\Domain\Employee\Models\Employee;
use App\Domain\Employee\Models\Location;
use App\Domain\Hiring\Models\JobOpening;
use App\Domain\Employee\Models\Department;
use App\Domain\Employee\Models\Designation;
use App\Domain\Employee\Models\EmploymentStatus;



class EditJobOpening
{
    /**
     * @param 
     * create job opening
     */
    public function execute($id)
    {
        $job = JobOpening::with('que')->where('id', $id)->first();
        $jobquestions= $job->que();
        $allquestions = Question::with('jobques')->get();

        $data['designations'] = Designation::all();
        $data['departments'] = Department::all();
        $data['locations'] = Location::all();
        $data['employees'] = Employee::all();
        $data['employmentStatus'] = EmploymentStatus::all();
        $data['jobquestions'] = $jobquestions;
        $data['allquestions'] = $allquestions;
        $data['job'] = $job;
        return $data;
    }
}
