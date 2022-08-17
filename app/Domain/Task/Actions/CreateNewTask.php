<?php


namespace App\Domain\Task\Actions;

use App\Domain\Employee\Models\Department;
use App\Domain\Employee\Models\Designation;
use App\Domain\Employee\Models\Division;
use App\Domain\Employee\Models\Employee;
use App\Domain\Employee\Models\EmploymentStatus;
use App\Domain\Employee\Models\Location;
use App\Domain\Task\Models\TaskCategory;
use App\Models\Company\Document;

class CreateNewTask
{
    public function execute($task_type)
    {
        $data['employees'] = Employee::where('status', '!=', '0')->get();
        $data['taskCategory'] = TaskCategory::where('type', $task_type)->get();
        $data['documents'] = Document::all();
        $data['departments'] = Department::all();
        $data['locations'] = Location::all();
        $data['designations'] = Designation::all();
        $data['divisions'] = Division::all();
        $data['employmentStatus'] = EmploymentStatus::all();

        return $data;
    }
}
