<?php


namespace App\Domain\Task\Actions;

use App\Domain\Task\Models\Task;

class GetTask
{
    public function execute()
    {
        return Tasks::all();
    }
}
