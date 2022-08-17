<?php


namespace App\Domain\Task\Actions;

use App\Domain\Task\Models\Task;
use App\Domain\Task\Models\TaskAttachmentTemplate;
use App\Domain\Task\Models\TaskRequiredForFilter;

class AddTask
{
    /**
     *
     * @param $data
     * */
    public function execute($request, $type)
    {

//        $task = new Tasks();
//        $task->task_name = $data['taskName'];
//        $task->task_category = $data['taskCategory'];
//        $task->task_description = $data['description'];
//        $task->task_type = $data['taskType'];
//        $task->save();
//        (new AddEmployeeTask())->execute($data, $task->id);
//
//        if (isset($data['optionalDocument'])) {
//            (new assignDocument())->execute($data, $task->id);
//        }

        $task = new Task();
        $task->name = $request->taskName;
        $task->category = $request->taskCategory;
        $task->description = $request->description;
        $task->type = $request->taskType;
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

//            $employeeTask->status_value = $status_value;
        }
        if($request->requiredFor == 'some')
        {
            $task->assigned_for_all_employees = 0;
        }

        $task->save();

        //add employees list in requiredFor
        //$status = checkbox (on)
        if(isset($request->departments)) {
            foreach ($request->departments as $department_id => $status) {
                $requiredFor = new TaskRequiredForFilter();
                $requiredFor->task_id = $task->id;
                $requiredFor->filter_type = "department";
                $requiredFor->filter_id = $department_id;
                $requiredFor->save();

            }
        }
        if(isset($request->divisions)) {
            foreach ($request->divisions as $division_id => $cb_status) {
                $requiredFor = new TaskRequiredForFilter();
                $requiredFor->task_id = $task->id;
                $requiredFor->filter_type = "division";
                $requiredFor->filter_id = $division_id;
                $requiredFor->save();

            }
        }
        if(isset($request->locations)) {
            foreach ($request->locations as $location_id => $cb_status) {
                $requiredFor = new TaskRequiredForFilter();
                $requiredFor->task_id = $task->id;
                $requiredFor->filter_type = "location";
                $requiredFor->filter_id = $location_id;
                $requiredFor->save();

            }
        }
        //job title
        if(isset($request->designations)) {
            foreach ($request->designations as $designation_id => $cb_status) {
                $requiredFor = new TaskRequiredForFilter();
                $requiredFor->task_id = $task->id;
                $requiredFor->filter_type = "designation";
                $requiredFor->filter_id = $designation_id;
                $requiredFor->save();

            }
        }
        if(isset($request->employmentStatuses)) {
            foreach ($request->employmentStatuses as $employmentStatus_id => $cb_status) {
                $requiredFor = new TaskRequiredForFilter();
                $requiredFor->task_id = $task->id;
                $requiredFor->filter_type = "employment-status";
                $requiredFor->filter_id = $employmentStatus_id;
                $requiredFor->save();

            }
        }

        //task template attachments
        if(isset($request->documents)) {
            foreach ($request->documents as $file_id => $status) {
                $taskFile = new TaskAttachmentTemplate();
                $taskFile->document_id = $file_id;
                $taskFile->task_id = $task->id;
                $taskFile->save();
            }
        }

    }
}
