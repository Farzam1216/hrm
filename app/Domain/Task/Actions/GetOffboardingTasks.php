<?php


namespace App\Domain\Task\Actions;

use App\Domain\Task\Models\Task;

class GetOffboardingTasks
{
    public function execute()
    {
        return Task::where('type', 1)->get();
    }
}
