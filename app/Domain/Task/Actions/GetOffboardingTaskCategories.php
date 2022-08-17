<?php


namespace App\Domain\Task\Actions;

use App\Domain\Task\Models\TaskCategory;

class GetOffboardingTaskCategories
{
    public function execute()
    {
        return TaskCategory::where('type', 1)->get();
    }
}
