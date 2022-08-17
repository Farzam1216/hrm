<?php


namespace App\Domain\Task\Actions;

use App\Domain\Task\Models\TaskCategory;

class GetAllTaskCategories
{
    public function execute()
    {
        return TaskCategory::all();
    }
}
