<?php


namespace App\Domain\Task\Actions;

use App\Domain\Employee\Models\Department;
use App\Domain\Employee\Models\Designation;
use App\Domain\Employee\Models\Division;
use App\Domain\Employee\Models\Employee;
use App\Domain\Employee\Models\EmploymentStatus;
use App\Domain\Employee\Models\Location;
use App\Domain\Task\Models\EmployeeTask;
use App\Domain\Task\Models\TaskAttachmentTemplate;
use App\Domain\Task\Models\TaskCategory;
use App\Domain\Task\Models\Task;
use App\Domain\Task\Models\TaskRequiredForFilter;
use App\Models\Company\Document;
use Illuminate\Support\Facades\Auth;

class EditTask
{
    public function execute($id, $task_type)
    {
//        $data['departmentCheck'] = (new CheckEmployeeDepartments())->execute($id);
//        $data['locationCheck'] = (new CheckEmployeeLocations())->execute($id);
//        $data['employmentStatusCheck'] = (new CheckEmployeeEmploymentStatuses())->execute($id);
        $data['employees'] = Employee::where('status', '!=', '0')->get();
        $data['task']= Task::find($id);
        $data['taskCategory'] = TaskCategory::where('type', $task_type)->get();
        $data['taskDocuments'] = TaskAttachmentTemplate::where('task_id', $id)->get();
//        $data['employeeTask'] = EmployeeTask::where('task_id', $id)->where('assigned_by', Auth::user()->id)->first();
        $data['documents'] = Document::all();
        $data['departments'] = Department::all();
        $data['taskDepartments'] = TaskRequiredForFilter::where(['task_id' => $id,
            'filter_type' => 'department'])->get();
        $data['locations'] = Location::all();
        $data['taskLocations'] = TaskRequiredForFilter::where(['task_id' => $id,
            'filter_type' => 'location'])->get();
        $data['designations'] = Designation::all();
        $data['taskDesignations'] = TaskRequiredForFilter::where(['task_id' => $id,
            'filter_type' => 'designation'])->get();
        $data['divisions'] = Division::all();
        $data['taskDivisions'] = TaskRequiredForFilter::where(['task_id' => $id,
            'filter_type' => 'division'])->get();
        $data['employmentStatus'] = EmploymentStatus::all();
        $data['taskEmploymentStatus'] = TaskRequiredForFilter::where(['task_id' => $id,
            'filter_type' => 'employment-status'])->get();

        return $data;
    }
}
