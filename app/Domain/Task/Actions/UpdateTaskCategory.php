<?php

namespace App\Domain\Task\Actions;

use App\Domain\Task\Models\TaskCategory;

class UpdateTaskCategory
{
    public function execute($request, $id)
    {
        $taskCategory = TaskCategory::find($id);
        $taskCategory->task_category_name = $request->task_category_name;
        $taskCategory->save();
        return $taskCategory;
    }
}