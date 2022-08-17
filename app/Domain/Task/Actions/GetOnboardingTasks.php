<?php


namespace App\Domain\Task\Actions;

use App\Domain\Task\Models\Task;

class GetOnboardingTasks
{
    public function execute()
    {
        return Task::where('type', 0)->get();
    }
}
