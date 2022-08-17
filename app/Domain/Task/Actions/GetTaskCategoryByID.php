<?php


namespace App\Domain\Task\Actions;


use App\Domain\Task\Models\TaskCategory;

class GetTaskCategoryByID
{
    public function execute($id)
    {
        return TaskCategory::where('id', $id)->first();
    }
}