<?php


namespace App\Domain\Task\Actions;

use App\Domain\Task\Models\TaskCategory;

class GetOnboardingTaskCategories
{
    public function execute()
    {
        return TaskCategory::where('type', 0)->get();
    }
}
