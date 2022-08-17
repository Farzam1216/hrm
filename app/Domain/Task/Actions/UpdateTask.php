<?php

namespace App\Domain\Task\Actions;

use App\Domain\Task\Models\Task;
use App\Domain\Task\Models\TaskAttachmentTemplate;
use App\Domain\Task\Models\TaskRequiredForFilter;

class UpdateTask
{
    /**
     * @param $data
     * @param $id
     */
    public function execute($request, $id,$type)
    {
//        $firstAssignment = EmployeeTask::where('task_id', $id)->where('assigned_by', Auth::user()->id)->first();
//        if ($firstAssignment->assigned_to != $data['assignedTo']) {
//            (new UpdateAssignedto())->execute($data, $id);
//        }
//        (new UpdateTaskModel())->execute($data, $id);
//        (new UpdateEmployeeTaskAssign())->execute($data, $id);
//        (new updateTaskDocument())->execute($data, $id);

        $task = Task::find($id);
        $task->name = $request->taskName;
        $task->category = isset($request->taskCategory)?intval($request->taskCategory):null;
        $task->description = $request->description;
        $task->type = isset($request->taskType)?intval($request->taskType):null;
        $task->assigned_to = $request->assignedTo;
        if($type == 0)
        {
            $date_hire_termination='on_hire_date';
        }
        else
        {
            $date_hire_termination='on_termination_date';

        }
        if ($request->dueDate['status'] == 'none' || $request->dueDate['status'] == $date_hire_termination) {
            $task->due_date = $request->dueDate['status'];
        }  elseif ($request->dueDate['status'] == 'specific_date') {
            if(isset($request->dueDate['days']))
            {
                $status_value = $request->dueDate['days'] . $request->dueDate['duration'];
                $task->due_date = $status_value;
                $task->period = $request->dueDate['period'];
            }
            else
            {
                $task->due_date="none";
            }

        }
        if($request->requiredFor == 'some')
        {
            $task->assigned_for_all_employees = 0;
        }

        $task->save();


        $taskDepartments=$task->taskRequiredForFilters()->where('filter_type', 'department')->get();
        //remove changed departments
        if($taskDepartments->isNotEmpty())
        {
            foreach ($taskDepartments as $taskDepartment)
            {
                if(isset($request->departments)) {
                    if(!isset($request->departments[$taskDepartment->filter_id]))
                    {
                        $taskDepartment->delete();

                    }

                }
                else
                {
                    $taskDepartment->delete();
                }
            }
        }

        if(isset($request->departments)) {
            foreach ($request->departments as $department_id => $status) {
                if(!($taskDepartments->isNotEmpty() && $taskDepartments->where('filter_id',$department_id)->first()))
                {
                    $requiredFor = new TaskRequiredForFilter();
                    $requiredFor->task_id = $task->id;
                    $requiredFor->filter_type = "department";
                    $requiredFor->filter_id = $department_id;
                    $requiredFor->save();
                }


            }
        }

        $taskDivisions=$task->taskRequiredForFilters()->where('filter_type', 'division')->get();
        //remove changed divisions
        if($taskDivisions->isNotEmpty()) {
            foreach ($taskDivisions as $taskDivision) {
                if (isset($request->divisions)) {
                    if (!isset($request->divisions[$taskDivision->filter_id])) {
                        $taskDivision->delete();
                    }

                } else {
                    $taskDivision->delete();
                }
            }
        }
        if(isset($request->divisions)) {
            foreach ($request->divisions as $division_id => $cb_status) {
                if(!($taskDivisions->isNotEmpty() && $taskDivisions->where('filter_id',$division_id)->first())) {
                    $requiredFor = new TaskRequiredForFilter();
                    $requiredFor->task_id = $task->id;
                    $requiredFor->filter_type = "division";
                    $requiredFor->filter_id = $division_id;
                    $requiredFor->save();
                }

            }
        }

        $taskLocations=$task->taskRequiredForFilters()->where('filter_type', 'location')->get();
        //remove changed locations
        if($taskLocations->isNotEmpty()) {
            foreach ($taskLocations as $taskLocation) {
                if (isset($request->locations)) {
                    if (!isset($request->locations[$taskLocation->filter_id])) {
                        $taskLocation->delete();
                    }
                } else {
                    $taskLocation->delete();
                }
            }
        }
        if(isset($request->locations)) {
            foreach ($request->locations as $location_id => $cb_status) {
                if(!($taskLocations->isNotEmpty() && $taskLocations->where('filter_id',$location_id)->first())) {
                    $requiredFor = new TaskRequiredForFilter();
                    $requiredFor->task_id = $task->id;
                    $requiredFor->filter_type = "location";
                    $requiredFor->filter_id = $location_id;
                    $requiredFor->save();
                }

            }
        }

        $taskDesignations=$task->taskRequiredForFilters()->where('filter_type', 'designation')->get();
        //remove changed designations
        if($taskDesignations->isNotEmpty()) {
            foreach ($taskDesignations as $taskDesignation) {
                if (isset($request->designations)) {
                    if (!isset($request->designations[$taskDesignation->filter_id])) {
                        $taskDesignation->delete();
                    }

                } else {
                    $taskDesignation->delete();
                }
            }
        }
        //job title
        if(isset($request->designations)) {
            foreach ($request->designations as $designation_id => $cb_status) {
                if(!($taskDesignations->isNotEmpty() && $taskDesignations->where('filter_id',$designation_id)->first())) {
                    $requiredFor = new TaskRequiredForFilter();
                    $requiredFor->task_id = $task->id;
                    $requiredFor->filter_type = "designation";
                    $requiredFor->filter_id = $designation_id;
                    $requiredFor->save();
                }

            }
        }

        $taskEmploymentStatus=$task->taskRequiredForFilters()->where('filter_type', 'employment-status')->get();
        //remove changed Employment Statuses
        if($taskEmploymentStatus->isNotEmpty())
        {
            foreach ($taskEmploymentStatus as $taskEmployment)
            {
                if(isset($request->employmentStatuses)) {
                    if(!isset($request->employmentStatuses[$taskEmployment->filter_id]))
                    {

                        $taskEmployment->delete();                    }

                }
                else
                {
                    $taskEmployment->delete();
                }
            }
        }

        if(isset($request->employmentStatuses)) {
            foreach ($request->employmentStatuses as $employmentStatus_id => $cb_status) {
                if(!($taskEmploymentStatus->isNotEmpty() && $taskEmploymentStatus->where('filter_id',$employmentStatus_id)->first())) {
                    $requiredFor = new TaskRequiredForFilter();
                    $requiredFor->task_id = $task->id;
                    $requiredFor->filter_type = "employment-status";
                    $requiredFor->filter_id = $employmentStatus_id;
                    $requiredFor->save();
                }

            }
        }


        $taskDocuments=$task->taskDocuments;
        //remove changed Employment Statuses
        if($taskDocuments->isNotEmpty()){
            foreach ($taskDocuments as $taskDocument)
            {
                if(isset($request->documents)) {
                    if(!isset($request->documents[$taskDocument->document_id]))
                    {
                        $taskDocument->delete();
                    }

                }
                else
                {
                    $taskDocument->delete();
                }
            }
        }


        //task template attachments
        if(isset($request->documents)) {
            foreach ($request->documents as $file_id => $status) {
                if(!$taskDocuments->where('document_id',$file_id)->first()) {
                    $taskFile = new TaskAttachmentTemplate();
                    $taskFile->document_id = $file_id;
                    $taskFile->task_id = $task->id;
                    $taskFile->save();
                }
            }
        }
        if($request->apply_to_all == "true")
        {
            //RUN THE JOB TO UPDATE EMPLOYEE TASKS
        }
    }
}