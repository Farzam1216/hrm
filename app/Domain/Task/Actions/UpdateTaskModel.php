<?php


namespace App\Domain\Task\Actions;

use App\Domain\Task\Models\Task;

class UpdateTaskModel
{
    /**
     * @param $data
     * @param $id
     * @return void
     */
    public function execute($data, $id)
    {
        $task = Tasks::find($id);
        if ($task->task_name != $data['taskName']) {
            $task->task_name = $data['taskName'];
            $task->task_description = $data['description'];
            $task->task_category = intval($data['taskCategory']);
            $task->task_type = intval($data['taskType']);
            $task->save();
        } elseif ($task->task_category != $data['taskCategory']) {
            $task->task_name = $data['taskName'];
            $task->task_description = $data['description'];
            $task->task_category = $data['taskCategory'];
            $task->task_type = $data['taskType'];
            $task->save();
        } elseif ($task->task_description != $data['description']) {
            $task->task_name = $data['taskName'];
            $task->task_description = $data['description'];
            $task->task_category = $data['taskCategory'];
            $task->task_type = $data['taskType'];
            $task->save();
        } elseif ($task->task_type != $data['taskType']) {
            $task->task_name = $data['taskName'];
            $task->task_description = $data['description'];
            $task->task_category = $data['taskCategory'];
            $task->task_type = $data['taskType'];
            $task->save();
        }
    }
}
