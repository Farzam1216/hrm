<?php


namespace App\Domain\Task\Actions;

use App\Domain\Task\Models\TaskCategory;
use Illuminate\Support\Facades\Session;

class AddTaskCategory
{
    public function execute($request, $task_type)
    {
        $task_category_exist = TaskCategory::where(['task_category_name' => $request->task_category_name, 'type' => $task_type])->first();
        if ($task_category_exist == null) {
            $task_category_exist = TaskCategory::create([
                'task_category_name' => $request->task_category_name,
                'type' => $task_type,
            ]);
            return true;
            // Session::flash('success', trans('language.Task Category is created successfully'));
        } else {
            return false;
            // Session::flash('error', trans('language.Task Category with this name already exist'));
        }
    }
}
